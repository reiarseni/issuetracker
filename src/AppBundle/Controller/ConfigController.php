<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Form\Model\ConfigModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Config controller.
 *
 * @Route("config")
 */
class ConfigController extends Controller
{
    /**
     * Displays a form to edit an existing config entity.
     *
     * @Route("/", name="config_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        $configModel = new ConfigModel();

        $em = $this->get('doctrine.orm.entity_manager');

        $repoConfig = $em->getRepository('AppBundle:Config');

        $configModel->setNombre($repoConfig->getValue('app.nombre'));
        $configModel->setEmail($repoConfig->getValue('app.email'));
        $configModel->setFacebookUrl($repoConfig->getValue('social.facebook'));
        $configModel->setMostrarContacto($repoConfig->getValue('app.mostrar_contacto'));
        $configModel->setEnMantenimiento($repoConfig->getValue('app.en_mantenimiento'));
        $configModel->setVersion($repoConfig->getValue('app.version'));

        $form = $this->createForm('AppBundle\Form\ConfigType', $configModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('config_manager')->editar($configModel);
            $this->get('session')->getFlashBag()->add('success', sprintf('Se modifico las CONFIGURACIONES satisfactoriamente.'));

            return $this->redirectToRoute('config_edit');
        }

        return $this->render('config/edit.html.twig', [
            'config' => $configModel,
            'edit_form' => $form->createView(),
        ]);
    }
}
