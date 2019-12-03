<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Requirement;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Requirement controller.
 *
 * @Route("")
 */
class RequirementController extends Controller
{
    /**
     * Lists all requirement.
     *
     * @Route("/requirement", name="requirement_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $qb = $em->createQueryBuilder()
            ->select('i')
            ->from('AppBundle:Requirement', 'i')->orderBy('i.createdAt', 'DESC');

        return $this->render('requirement/index.html.twig', [
            'entities' => $qb->getQuery()->getResult(),
        ]);
    }

    /**
     * Creates a new requirement entity.
     *
     * @Route("requirement/new", name="requirement_new")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     */
    public function newAction(Request $request)
    {
        $requirement = new Requirement();
        $form = $this->createForm('AppBundle\Form\RequirementType', $requirement);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->get('requirement_manager')->crear($requirement);
                $this->get('session')->getFlashBag()->add('success', sprintf('Se adiciono el Requirement satisfactoriamente.'));

                return $this->redirectToRoute('requirement_index');
            }
        }

        return $this->render('requirement/new.html.twig', [
            'requirement' => $requirement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing requirement entity.
     *
     * @Route("requirement/{id}/edit", name="requirement_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request     $request
     * @param Requirement $requirement
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Requirement $requirement)
    {
        $editForm = $this->createForm('AppBundle\Form\RequirementType', $requirement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->get('requirement_manager')->editar($requirement);
            $this->get('session')->getFlashBag()->add('success', sprintf('Se modifico el Requirement satisfactoriamente.'));

            return $this->redirectToRoute('requirement_show', ['id' => $requirement->getId()]);
        }

        return $this->render('requirement/edit.html.twig', [
            'requirement' => $requirement,
            'edit_form' => $editForm->createView(),
        ]);
    }

    /**
     * Display an existing requirement entity.
     *
     * @Route("requirement/{id}/show", name="requirement_show")
     * @Method({"GET", "POST"})
     *
     * @param Requirement $requirement
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Requirement $requirement)
    {
        return $this->render('requirement/show.html.twig', [
            'requirement' => $requirement,
        ]);
    }

    /**
     * Creates a new requirement comment entity.
     *
     * @Route("requirement/{requirement}/comment/new", name="requirement_comment_new")
     * @Method({"GET", "POST"})
     *
     * @param Request     $request
     * @param Requirement $requirement
     * @ParamConverter("requirement", options={"mapping" = {"requirement" = "id"}})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newCommentAction(Request $request, Requirement $requirement)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment, [
            'method' => 'POST',
            'action' => $this->generateUrl('requirement_comment_new',
                ['requirement' => $requirement->getId()]
            ),
        ]);

        $comment->setRequirement($requirement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('requirement_manager')->crearComment($comment);
            $this->get('session')->getFlashBag()->add('success', sprintf('Se adiciono el Comment satisfactoriamente.'));

            return $this->redirectToRoute('requirement_show', ['id' => $requirement->getId()]);
        }

        return $this->render('requirement/comment/new.html.twig', [
            'requirement' => $requirement,
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing requirement comment entity.
     *
     * @Route("comment/{requirement}/{id}", name="requirement_comment_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request     $request
     * @param Requirement $requirement
     * @param Comment     $comment
     */
    public function editCommentAction(Request $request, Requirement $requirement, Comment $comment)
    {
        $editForm = $this->createForm(CommentType::class, $comment, [
            'method' => 'POST',
            'action' => $this->generateUrl('requirement_comment_edit',
                ['requirement' => $requirement->getId(), 'id' => $comment->getId()]
            ),
        ]);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($comment);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', sprintf('Se modifico el COMMENT satisfactoriamente.'));

            return $this->redirectToRoute('requirement_show', ['id' => $requirement->getId()]);
        }

        return $this->render('requirement/comment/edit.html.twig', [
            'requirement' => $requirement,
            'form' => $editForm->createView(),
        ]);
    }

    /**
     * Deletes a Requirement entity.
     *
     * @Route("/{id}/delete", name="requirement_delete")
     * @Method("POST")
     *
     * @param Request     $request
     * @param Requirement $requirement
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, Requirement $requirement)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('requirement_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($requirement);
        $em->flush();

        $this->addFlash('success', 'Eliminacion con exito');

        return $this->redirectToRoute('requirement_index');
    }

//    /**
//     * @Route("notify_idx", name="notify_idx", options={"expose":true})
//     * @Method({"GET"})
//     */
//    public function indexNotifyAction(Request $request)
//    {
//        $qb = $this->get('doctrine.orm.entity_manager')->getRepository("AppBundle:UserLoginLog")
//            ->createQueryBuilder('ull')
//            ->orderBy('ull.createdAt', 'DESC');
//
//        $qb->setFirstResult(0);
//        $qb->setMaxResults(7);
//
//        $entities = $qb->getQuery()->getResult();
//
//        return $this->render('@Notify/Notify/notify.html.twig', array(
//            'entities' => $entities
//        ));
//    }
}
