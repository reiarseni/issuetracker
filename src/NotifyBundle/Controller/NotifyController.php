<?php

declare(strict_types=1);

namespace NotifyBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use PagerBundle\Pagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Notify controller.
 *
 * @Route("notify")
 */
class NotifyController extends Controller
{
    use DoctrineControllerTrait;

    /**
     * Lists all user entities.
     *
     * @Route("/", name="notify_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $qb = $this->repo('NotifyBundle:UserLoginLog')
            ->createQueryBuilder('n');

        $options = [
            'sorters' => ['n.createdAt' => 'DESC'], // sorted by language code by default
            'applyFilter' => [$this, 'notifyFilters'], // custom filter handling
            'limit' => 10,
        ];

        $notifies = new Pagination($qb, $request, $options);

        Pagination::$defaults = array_merge(Pagination::$defaults, ['limit' => 10]);

        return $this->render('@Notify/Main/notifies.html.twig', [
            'notifies' => $notifies,
        ]);
    }

    /**
     * @Route("/menu", name="notify_menu", options={"expose" = true})
     * @Method({"GET"})
     */
    public function menuAction(Request $request)
    {
        $qb = $this->get('doctrine.orm.entity_manager')->getRepository('NotifyBundle:UserLoginLog')
            ->createQueryBuilder('ull')
            ->orderBy('ull.createdAt', 'DESC');

        $qb->setFirstResult(0);
        $qb->setMaxResults(5);

        $entities = $qb->getQuery()->getResult();

        return $this->render('@Notify/Menu/notify.html.twig', [
            'entities' => $entities,
        ]);
    }

    public function notifyFilters(QueryBuilder $qb, $key, $val)
    {
        $val = trim($val);

        switch ($key) {
            case 'n.createdAt':
                if ($val) {
                    $qb->andWhere($qb->expr()->eq('n.createdAt', ':createdAt'));
                    $qb->setParameter('createdAt', $val);
                }
                break;
            case 'n.userName':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('n.userName', "'%{$val}%'"));
                }
                break;
            case 'n.showText':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('n.showText', "'%{$val}%'"));
                }
                break;
            case 'n.operation':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('n.operation', "'%{$val}%'"));
                }
                break;
            default:
                // if user attemps to filter by other fields, we restrict it
                throw new \Exception('filter not allowed');
        }
    }
}
