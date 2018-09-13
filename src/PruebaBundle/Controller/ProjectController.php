<?php

namespace PruebaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Util\StringUtil;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\QueryBuilder;
use PagerBundle\Pagination;
use PruebaBundle\Entity\Project;

class ProjectController extends Controller
{
    use DoctrineControllerTrait;

    public function projectFilters(QueryBuilder $qb, $key, $val)
    {
        switch ($key) {
            case 'p.code':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('p.code', "'%{$val}%'"));
                }
                break;
            case 'p.name':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('p.name', "'%{$val}%'"));
                }
                break;
            case 'p.hoursSpent':
                switch ($val) {
                    case 'lessThan10':
                        $qb->andWhere($qb->expr()->lt('p.hoursSpent', $qb->expr()->literal(10)));
                        break;
                    case 'upTo20':
                        $qb->andWhere($qb->expr()->lte('p.hoursSpent', $qb->expr()->literal(20)));
                        break;
                    case 'moreThan2weeks':
                        $qb->andWhere($qb->expr()->gte('p.hoursSpent', $qb->expr()->literal(80)));
                        break;
                    case 'overDeadline':
                        $qb->andWhere($qb->expr()->gt('p.hoursSpent', 'p.deadline'));
                        break;
                }
                break;
            case 'l.code':
                $qb->andWhere($qb->expr()->eq('l.code', ':code'));
                $qb->setParameter('code', $val);
                break;
            case 'p.enabled':
                if ($val != 'any') {
                    $qb->andWhere($qb->expr()->eq('p.enabled', ':enabled'));
                    $qb->setParameter('enabled', $val);
                }
                break;
            default:
                // if user attemps to filter by other fields, we restrict it
                throw new \Exception("filter not allowed");
        }
    }

    /**
     * @Method("GET")
     * @Template
     * @Route("/projects", name="projects")
     */
    public function indexAction(Request $request)
    {
        $qb = $this->repo("PruebaBundle:Project")
            ->createQueryBuilder('p')
            ->addSelect('l')
            ->innerJoin('p.language', 'l');

        $options = [
            'sorters' => ['p.id' => 'ASC'], // sorted by language code by default
            'filters' => ['p.hoursSpent' => 'overDeadline'], // we can apply a filter option by default
            'applyFilter' => [$this, 'projectFilters'], // custom filter handling
            'limit' => 10
        ];

        $languages = [
            Pagination::$filterAny => 'Any',
            'php' => 'PHP',
            'hs' => 'Haskell',
            'go' => 'Golang',
        ];

        $enableds = [
            Pagination::$filterAny => 'Any',
            1 => 'Enabled',
            0 => 'Disabled'
        ];

        $spentTimeGroups = [
            Pagination::$filterAny => 'Any',
            'lessThan10' => 'Less than 10h',
            'upTo20' => 'Up to 20h',
            'moreThan2weeks' => 'More than 2weeks',
            'overDeadline' => 'Over deadline',
        ];

        $projects = new Pagination($qb, $request, $options);

        Pagination::$defaults = array_merge(Pagination::$defaults, ['limit' => 10]);

        return compact('projects', 'languages', 'spentTimeGroups', 'enableds');
    }

    /**
     * @Method("GET")
     * @Template
     * @Route("/toggle/{id}", name="project_toggle")
     */
    public function toggleAction(Project $project, Request $request)
    {
        $project->setEnabled(!$project->getEnabled());
        $this->persist($project);
        $this->flush();

        // redirect to the list with the same filters applied as before
        return $this->redirect($this->generateUrl('projects', $request->query->all()));
    }

    /**
     * @Method("POST")
     * @Template
     * @Route("/projects/inlineedit", name="project_inlineedit")
     */
    public function inlineEditAction(Request $request)
    {

        try {

            $entityClass = 'PruebaBundle:' . 'Project';

            $params['id'] = trim($request->get('id')) ? trim($request->get('id')) : null;
            $params['field'] = trim($request->get('field')) ? trim($request->get('field')) : null;
            $params['value'] = trim($request->get('value')) ? trim($request->get('value')) : null;

            $em = $this->get('doctrine.orm.entity_manager');
            $mainRepo = $em->getRepository($entityClass);

            if ($params['id'] && $params['field']) {

                $meta = $em->getClassMetadata($entityClass);

                if (!$meta->hasField($params['field']) && !$meta->hasAssociation($params['field'])) {
                    return new JsonResponse(array('message' => 'El campo no existe'));
                }

                if ($meta->hasField($params['field'])) {
                    $mainObject = $mainRepo->find($params['id']);
                    $meta->getReflectionProperty($params['field'])->setValue($mainObject, $params['value']);
                    $em->persist($mainObject);
                    $em->flush();
                    return new JsonResponse(array('message' => 'ok'));
                }

                if ($meta->hasAssociation($params['field'])) {
                    $mainObject = $mainRepo->find($params['id']);
                    $assocClassName = $meta->getAssociationMapping($params['field'])['targetEntity'];
                    $assocRepo = $em->getRepository($assocClassName);
                    $assocObject = $assocRepo->find($params['value']);
                    $meta->getReflectionProperty($params['field'])->setValue($mainObject, $assocObject);
                    $em->persist($mainObject);
                    $em->flush();
                    return new JsonResponse(array('message' => 'ok'));
                }

                return new JsonResponse(array('message' => 'ok'));

            } else {
                return new JsonResponse(array('message' => 'No se paso el ID'), 500);
            }

        } catch (\Exception $e) {
            return new JsonResponse(array('message' => $e->getMessage()), 500);
        }

    }

    /**
     * @Method("GET")
     * @Template
     * @Route("/projects/inlineedit/getselect", name="project_inlineedit_getselect")
     */
    public function getSelectAction(Request $request)
    {

        try {

            $params['clase'] = trim($request->get('clase')) ? trim($request->get('clase')) : null;

            $params['selectedId'] = trim($request->get('selectedId')) ? trim($request->get('selectedId')) : null;

            if ($params['clase']) {

                $entityClass = 'PruebaBundle:' . $params['clase'];

                $em = $this->get('doctrine.orm.entity_manager');
                $entities = $repo = $em->getRepository($entityClass)->findAll();

                return $this->render('@Prueba/Project/select.html.twig', array(
                    'entities' => $entities,
                    'selected_value' => $params['selectedId']
                ));

            } else {

                return new JsonResponse(array('message' => 'No se paso el parametro clase'), 500);

            }

        } catch (\Exception $e) {
            return new JsonResponse(array('message' => $e->getMessage()), 500);
        }

    }
}
