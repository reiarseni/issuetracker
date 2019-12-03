<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\Changelog;
use AppBundle\Entity\Comment;
use AppBundle\Form\ChangelogType;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Changelog controller.
 *
 * @Route("")
 */
class ChangelogController extends Controller
{
    /**
     * Lists all Changelog.
     *
     * @Route("/changelog", name="changelog_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $qb = $em->createQueryBuilder()
            ->select('c')
            ->from('AppBundle:Changelog', 'c')->orderBy('c.createdAt', 'DESC');

        return $this->render('changelog/index.html.twig', [
            'entities' => $qb->getQuery()->getResult(),
        ]);
    }

    /**
     * Creates a new changelog entity.
     *
     * @Route("changelog/new", name="changelog_new")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     */
    public function newAction(Request $request)
    {
        $changelog = new Changelog();
        $form = $this->createForm(ChangelogType::class, $changelog);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            dump('issubmit');
            if ($form->isValid()) {
                dump('isvalid');
                $this->get('changelog_manager')->crear($changelog);
                $this->get('session')->getFlashBag()->add('success', sprintf('Se adiciono el Changelog satisfactoriamente.'));

                return $this->redirectToRoute('changelog_index');
            }

            dump($form->getErrors()[0]);
        }

        return $this->render('changelog/new.html.twig', [
            'form' => $form->createView(),
            'changelog' => $changelog,
        ]);
    }

    /**
     * Displays a form to edit an existing changelog entity.
     *
     * @Route("changelog/{id}/edit", name="changelog_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request   $request
     * @param Changelog $changelog
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Changelog $changelog)
    {
        $editForm = $this->createForm('AppBundle\Form\ChangelogType', $changelog);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->get('changelog_manager')->editar($changelog);
            $this->get('session')->getFlashBag()->add('success', sprintf('Se modifico el Changelog satisfactoriamente.'));

            return $this->redirectToRoute('changelog_show', ['id' => $changelog->getId()]);
        }

        return $this->render('changelog/edit.html.twig', [
            'changelog' => $changelog,
            'edit_form' => $editForm->createView(),
        ]);
    }

    /**
     * Display an existing changelog entity.
     *
     * @Route("changelog/{id}/show", name="changelog_show")
     * @Method({"GET", "POST"})
     *
     * @param Changelog $changelog
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Changelog $changelog)
    {
        return $this->render('changelog/show.html.twig', [
            'changelog' => $changelog,
        ]);
    }

    /**
     * Creates a new changelog comment entity.
     *
     * @Route("changelog/{changelog}/comment/new", name="changelog_comment_new")
     * @Method({"GET", "POST"})
     *
     * @param Request   $request
     * @param Changelog $changelog
     * @ParamConverter("changelog", options={"mapping" = {"changelog" = "id"}})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newCommentAction(Request $request, Changelog $changelog)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment, [
            'method' => 'POST',
            'action' => $this->generateUrl('changelog_comment_new',
                ['changelog' => $changelog->getId()]
            ),
        ]);

        $comment->setChangelog($changelog);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('changelog_manager')->crearChangelogComment($comment);
            $this->get('session')->getFlashBag()->add('success', sprintf('Se adiciono el Comment satisfactoriamente.'));

            return $this->redirectToRoute('changelog_show', ['id' => $changelog->getId()]);
        }

        return $this->render('changelog/comment/new.html.twig', [
            'changelog' => $changelog,
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing changelog comment entity.
     *
     * @Route("comment/{changelog}/{id}", name="changelog_comment_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request   $request
     * @param Changelog $changelog
     * @param Comment   $comment
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editCommentAction(Request $request, Changelog $changelog, Comment $comment)
    {
        $editForm = $this->createForm(CommentType::class, $comment, [
            'method' => 'POST',
            'action' => $this->generateUrl('changelog_comment_edit',
                ['changelog' => $changelog->getId(), 'id' => $comment->getId()]
            ),
        ]);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($comment);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', sprintf('Se modifico el COMMENT satisfactoriamente.'));

            return $this->redirectToRoute('changelog_show', ['id' => $changelog->getId()]);
        }

        return $this->render('changelog/comment/edit.html.twig', [
            'changelog' => $changelog,
            'form' => $editForm->createView(),
        ]);
    }

    /**
     * Deletes a Changelog entity.
     *
     * @Route("changelog/{id}/delete", name="changelog_delete")
     * @Method("POST")
     *
     * @param Request   $request
     * @param Changelog $changelog
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, Changelog $changelog)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('changelog_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($changelog);
        $em->flush();

        $this->addFlash('success', 'Eliminacion con exito');

        return $this->redirectToRoute('changelog_index');
    }
}
