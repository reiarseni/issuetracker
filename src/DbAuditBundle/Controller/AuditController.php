<?php

declare(strict_types=1);

namespace DbAuditBundle\Controller;

use DbAuditBundle\Entity\AuditLog;
use Doctrine\ORM\QueryBuilder;
use PagerBundle\Pagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AuditController extends Controller
{
    use DoctrineControllerTrait;

    public function filters(QueryBuilder $qb, $key, $val)
    {
        switch ($key) {
        case 'history':
            if ($val) {
                $orx = $qb->expr()->orX();
                $orx->add('s.fk = :fk');
                $orx->add('t.fk = :fk');

                $qb->andWhere($orx);
                $qb->setParameter('fk', (int) $val);
            }
            break;
        case 'class':
            $orx = $qb->expr()->orX();
            $orx->add('s.class = :class');
            $orx->add('t.class = :class');

            $qb->andWhere($orx);
            $qb->setParameter('class', $val);
            break;
        case 'blamed':
            if ('null' === $val) {
                $qb->andWhere($qb->expr()->isNull('a.blame'));
            } else {
                // this allows us to safely ignore empty values
                // otherwise if $qb is not changed, it would add where the string is empty statement.
                $qb->andWhere($qb->expr()->eq('b.fk', ':blame'));
                $qb->setParameter('blame', $val);
            }
            break;
        default:
            // if user attemps to filter by other fields, we restrict it
            throw new \Exception('filter not allowed');
        }
    }

    /**
     * @Method("GET")
     * @Template
     * @Route("/databaseaudit", name="databaseaudit")
     */
    public function indexAction(Request $request)
    {
        Pagination::$defaults = array_merge(Pagination::$defaults, ['limit' => 25]);

        $qb = $this->repo('DbAuditBundle:AuditLog')
            ->createQueryBuilder('a')
            ->addSelect('s', 't', 'b')
            ->innerJoin('a.source', 's')
            ->leftJoin('a.target', 't')
            ->leftJoin('a.blame', 'b');

        $options = [
            'sorters' => ['a.loggedAt' => 'DESC'],
            'applyFilter' => [$this, 'filters'],
        ];

        $sourceClasses = [
            Pagination::$filterAny => 'Any Source Class',
        ];

        foreach ($this->getDoctrine()->getManager()->getMetadataFactory()->getAllMetadata() as $meta) {
            if ($meta->isMappedSuperclass || 0 === strpos($meta->name, 'DbAuditBundle')) {
                continue;
            }
            $parts = explode('\\', $meta->name);
            $sourceClasses[$meta->name] = end($parts);
        }

        $users = [
            Pagination::$filterAny => 'Any User',
            'null' => 'Unknown',
        ];
        foreach ($this->repo('AppBundle:User')->findAll() as $user) {
            $users[$user->getId()] = (string) $user;
        }

        $logs = new Pagination($qb, $request, $options);

        return compact('logs', 'sourceClasses', 'users');
    }

    /**
     * @Route("/databaseaudit/diff/{id}", name="databaseaudit_diff")
     * @Method("GET")
     * @Template
     *
     * @param AuditLog $log
     */
    public function diffAction(AuditLog $log)
    {
        return compact('log');
    }
}
