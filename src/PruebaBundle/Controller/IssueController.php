<?php

namespace PruebaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\QueryBuilder;
use PagerBundle\Pagination;

class IssueController extends Controller
{
    use DoctrineControllerTrait;

    public function issueFilters(QueryBuilder $qb, $key, $val)
    {

        switch ($key) {
            case 'i.number':
                if ($val) {
                   // dump($key,$val); die;

                    $qb->andWhere($qb->expr()->like('i.number', "'%{$val}%'"));
                }
                break;
            case 'i.title':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('i.title', "'%{$val}%'"));
                }
                break;
            case 'i.category':
                $qb->andWhere($qb->expr()->eq('c.id', ':cid'));
                $qb->setParameter('cid', $val);
                break;
            case 'i.status':
                $qb->andWhere($qb->expr()->eq('s.id', ':sis'));
                $qb->setParameter('sis', $val);
                break;
            case 'i.priority':
                $qb->andWhere($qb->expr()->eq('p.id', ':pid'));
                $qb->setParameter('pid', $val);
                break;
            case 'i.type':
                $qb->andWhere($qb->expr()->eq('t.id', ':tid'));
                $qb->setParameter('tid', $val);
                break;
            default:
                // if user attemps to filter by other fields, we restrict it
                throw new \Exception("filter not allowed");
        }
    }

    /**
     * @Method("GET")
     * @Template(template="@Prueba/Issue/issues.html.twig")
     * @Route("/issues", name="issues")
     */
    public function indexAction(Request $request)
    {
        $qb = $this->repo("AppBundle:Issue")
            ->createQueryBuilder('i')
            ->addSelect('c')
            ->addSelect('s')
            ->addSelect('p')
            ->addSelect('t')
            ->leftJoin('i.category', 'c')
            ->leftJoin('i.status', 's')
            ->leftJoin('i.priority', 'p')
            ->leftJoin('i.type', 't')
            ->orderBy('i.createdAt', 'DESC');


        $options = [
            'sorters' => ['i.number' => 'DESC'], // sorted by language code by default
            //'filters' => ['p.hoursSpent' => 'overDeadline'], // we can apply a filter option by default
            'applyFilter' => [$this, 'issueFilters'], // custom filter handling
            'limit' => 50
        ];

        $categories =  $this->repo("AppBundle:Category")
            ->createQueryBuilder('c')
            ->select('c.id,c.name')
            ->orderBy('c.name')->getQuery()->getArrayResult(); //  dump($categories); die;
        $categoriesR = Array();
        $categoriesR['any'] = 'Any';
        foreach ($categories as $category) {
            $categoriesR[$category['id']] = $category['name'];
        }
        $categories = $categoriesR;



        $statuses =  $this->repo("AppBundle:IssueStatus")
            ->createQueryBuilder('s')
            ->select('s.id,s.name')
            ->orderBy('s.name')->getQuery()->getArrayResult();
        $statusesR = Array();
        $statusesR['any'] = 'Any';
        foreach ($statuses as $status) {
            $statusesR[$status['id']] = $status['name'];
        }
        $statuses = $statusesR;


        $priorities =  $this->repo("AppBundle:IssuePriority")
            ->createQueryBuilder('p')
            ->select('p.id,p.name')
            ->orderBy('p.name')->getQuery()->getArrayResult();
        $priorityR = Array();
        $priorityR['any'] = 'Any';
        foreach ($priorities as $priority) {
            $priorityR[$priority['id']] = $priority['name'];
        }
        $priorities = $priorityR;

        $types = $this->repo("AppBundle:IssueType")
            ->createQueryBuilder('t')
            ->select('t.id,t.name')
            ->orderBy('t.name')->getQuery()->getArrayResult();
        $typeR = Array();
        $typeR['any'] = 'Any';
        foreach ($types as $type) {
            $typeR[$type['id']] = $type['name'];
        }
        $types = $typeR;


        $issues = new Pagination($qb, $request, $options);

        Pagination::$defaults = array_merge(Pagination::$defaults, ['limit' => 10]);

        return compact('issues', 'categories', 'statuses', 'priorities', 'types');
    }

    /**
     * @Method("POST")
     * @Template
     * @Route("/issues/inlineedit", name="issue_inlineedit")
     */
    public function inlineEditAction(Request $request)
    {

        try {

            $entityClass = 'AppBundle:' . 'Issue';

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
     * @Route("/issues/inlineedit/getselect", name="issue_inlineedit_getselect")
     */
    public function getSelectAction(Request $request)
    {

        try {

            $params['clase'] = trim($request->get('clase')) ? trim($request->get('clase')) : null;

            $params['selectedId'] = trim($request->get('selectedId')) ? trim($request->get('selectedId')) : null;

            if ($params['clase']) {

                $entityClass = 'AppBundle:' . $params['clase'];

                $em = $this->get('doctrine.orm.entity_manager');
                $entities = $repo = $em->getRepository($entityClass)->findAll();

                return $this->render('@Prueba/Issue/select.html.twig', array(
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


    /**
     * @Route("/issue_searchbynumber", name="issue_searchbynumber", options={"expose":true})
     * @Method({"GET"})
     */
    public function searchByNumberAction(Request $request)
    {
        $number = trim( $request->get('number', '') );

        $param= array();
        $param['sorters[i.number]'] = 'DESC'; // = sorters[i.number]=DESC&filters[i.number]=259&page=1;
        $param['filters[i.number]'] = $number;
        $param['page'] = 1;


        // redirect to the list with the same filters applied as before
        return $this->redirect($this->generateUrl('issues', $param));
    }




}
