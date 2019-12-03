<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * DashBoard controller.
 *
 * @Route
 */
class DashBoardController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');

        return $this->redirectToRoute('issue_index');
    }
}
