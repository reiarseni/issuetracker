<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Issue;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Issue controller.
 *
 * @Route("")
 */
class IssueController extends Controller
{

    /**
     * Lists all issue entities.
     *
     * @Route("/issue", name="issue_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $users = $em->getRepository('AppBundle:Issue')->findAll();

        return $this->render('issue/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Creates a new issue entity.
     *
     * @Route("issue/new", name="issue_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $issue = new Issue();
        $form = $this->createForm('AppBundle\Form\IssueType', $issue);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->get('issue_manager')->crear($issue);
                $this->get('session')->getFlashBag()->add('success', sprintf('Se adiciono el ISSUE satisfactoriamente.'));
                return $this->redirectToRoute('issue_index');
            }
        }

        return $this->render('issue/new.html.twig', array(
            'issue' => $issue,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing issue entity.
     *
     * @Route("issue/{id}/edit", name="issue_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Issue $issue)
    {
        $statusOld = $issue->getStatus();

        $editForm = $this->createForm('AppBundle\Form\IssueType', $issue);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->get('issue_manager')->editar($issue, $statusOld);
            $this->get('session')->getFlashBag()->add('success', sprintf('Se modifico el ISSUE satisfactoriamente.'));
            return $this->redirectToRoute('issue_show', array('id'=>$issue->getId()));
        }

        return $this->render('issue/edit.html.twig', array(
            'issue' => $issue,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Display an existing issue entity.
     *
     * @Route("issue/{id}/show", name="issue_show")
     * @Method({"GET", "POST"})
     */
    public function showAction(Request $request, Issue $issue)
    {
        return $this->render('issue/show.html.twig', array(
            'issue' => $issue,
        ));
    }

    /**
     * Creates a new issue comment entity.
     *
     * @Route("issue/{issue}/comment/new", name="issue_comment_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Issue $issue
     * @ParamConverter("issue", options={"mapping": {"issue": "id"}})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newCommentAction(Request $request, Issue $issue)
    {
        $comment = new Comment();

        $form = $this->createForm( CommentType::class, $comment, array(
            'method' => 'POST',
            'action' => $this->generateUrl('issue_comment_new',
                array('issue' => $issue->getId())
            ),
        ));

        $comment->setIssue($issue);

        $form->handleRequest($request);

        if  ($form->isSubmitted() && $form->isValid() ) {
                $this->get('issue_manager')->crearComment($comment);
                $this->get('session')->getFlashBag()->add('success', sprintf('Se adiciono el Comment satisfactoriamente.'));
                return $this->redirectToRoute('issue_show', array('id' => $issue->getId()));
        }

        return $this->render('issue/comment/new.html.twig', array(
            'issue' => $issue,
            'comment' => $comment,
            'form' => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing issue comment entity.
     *
     * @Route("comment/{issue}/{id}", name="issue_comment_edit")
     * @Method({"GET", "POST"})
     */
    public function editCommentAction(Request $request, Issue $issue, Comment $comment)
    {

        $editForm = $this->createForm( CommentType::class, $comment, array(
            'method' => 'POST',
            'action' => $this->generateUrl('issue_comment_edit',
                array('issue' => $issue->getId(), 'id'=>$comment->getId())
            ),
        ));

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em =$this->get('doctrine.orm.entity_manager');
            $em->persist($comment);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', sprintf('Se modifico el COMMENT satisfactoriamente.'));
            return $this->redirectToRoute('issue_show', array('id'=>$issue->getId()));
        }

        return $this->render('issue/comment/edit.html.twig', array(
            'issue' => $issue,
            'form' => $editForm->createView()
        ));
    }


    /**
     * Deletes a Issue entity.
     *
     * @Route("/{id}/delete", name="issue_delete")
     * @Method("POST")
     */
    public function delete(Request $request, Issue $issue)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('issue_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($issue);
        $em->flush();

        $this->addFlash('success', 'Eliminacion con exito');

        return $this->redirectToRoute('issue_index');
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
