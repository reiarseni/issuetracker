<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * DashBoard controller.
 *
 * @Route()
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
