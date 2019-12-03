<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Issue;
use AppBundle\Form\CommentType;
use AppBundle\Manager\CurlRequestResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

        $qb = $em->createQueryBuilder()
            ->select('i')
            ->from('AppBundle:Issue', 'i')->orderBy('i.number', 'DESC');

        return $this->render('issue/index.html.twig', [
            'entities' => $qb->getQuery()->getResult(),
        ]);
    }

    /**
     * Creates a new issue entity.
     *
     * @Route("issue/new", name="issue_new")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     */
    public function newAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $issue = new Issue();

        $issue->setStatus($em->getRepository('AppBundle:IssueStatus')->findOneBy(['name' => 'RECIBIDO']));
        $issue->setPriority($em->getRepository('AppBundle:IssuePriority')->findOneBy(['name' => 'MEDIA']));
        $issue->setType($em->getRepository('AppBundle:IssueType')->findOneBy(['name' => 'BUG FIX']));
        $issue->setReportedBy($em->getRepository('AppBundle:User')->findOneBy(['username' => 'steph']));
        $issue->setAssignedTo($em->getRepository('AppBundle:User')->findOneBy(['username' => 'rei']));

        $form = $this->createForm('AppBundle\Form\IssueType', $issue);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->get('issue_manager')->crear($issue);
                $this->get('session')->getFlashBag()->add('success', sprintf('Se adiciono el ISSUE satisfactoriamente.'));

                return $this->redirectToRoute('issue_index');
            }
        }

        return $this->render('issue/new.html.twig', [
            'issue' => $issue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing issue entity.
     *
     * @Route("issue/{id}/edit", name="issue_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Issue   $issue
     */
    public function editAction(Request $request, Issue $issue)
    {
        $statusOld = $issue->getStatus();

        $editForm = $this->createForm('AppBundle\Form\IssueType', $issue);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->get('issue_manager')->editar($issue, $statusOld);
            $this->get('session')->getFlashBag()->add('success', sprintf('Se modifico el ISSUE satisfactoriamente.'));

            return $this->redirectToRoute('issue_show', ['id' => $issue->getId()]);
        }

        return $this->render('issue/edit.html.twig', [
            'issue' => $issue,
            'edit_form' => $editForm->createView(),
        ]);
    }

    /**
     * Display an existing issue entity.
     *
     * @Route("issue/{id}/show", name="issue_show")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Issue   $issue
     */
    public function showAction(Request $request, Issue $issue)
    {
        return $this->render('issue/show.html.twig', [
            'issue' => $issue,
        ]);
    }

    /**
     * Creates a new issue comment entity.
     *
     * @Route("issue/{issue}/comment/new", name="issue_comment_new")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Issue   $issue
     * @ParamConverter("issue", options={"mapping" = {"issue" = "id"}})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newCommentAction(Request $request, Issue $issue)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment, [
            'method' => 'POST',
            'action' => $this->generateUrl('issue_comment_new',
                ['issue' => $issue->getId()]
            ),
        ]);

        $comment->setIssue($issue);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('issue_manager')->crearComment($comment);
            $this->get('session')->getFlashBag()->add('success', sprintf('Se adiciono el Comment satisfactoriamente.'));

            return $this->redirectToRoute('issue_show', ['id' => $issue->getId()]);
        }

        return $this->render('issue/comment/new.html.twig', [
            'issue' => $issue,
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing issue comment entity.
     *
     * @Route("comment/{issue}/{id}", name="issue_comment_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Issue   $issue
     * @param Comment $comment
     *
     * @return Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editCommentAction(Request $request, Issue $issue, Comment $comment)
    {
        $editForm = $this->createForm(CommentType::class, $comment, [
            'method' => 'POST',
            'action' => $this->generateUrl('issue_comment_edit',
                ['issue' => $issue->getId(), 'id' => $comment->getId()]
            ),
        ]);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($comment);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', sprintf('Se modifico el COMMENT satisfactoriamente.'));

            return $this->redirectToRoute('issue_show', ['id' => $issue->getId()]);
        }

        return $this->render('issue/comment/edit.html.twig', [
            'issue' => $issue,
            'form' => $editForm->createView(),
        ]);
    }

    /**
     * Deletes a Issue entity.
     *
     * @Route("/{id}/delete", name="issue_delete")
     * @Method("POST")
     *
     * @param Request $request
     * @param Issue   $issue
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

    /**
     * @Route("issue/newrest", name="issue_new_rest")
     * @Method({"POST"})
     *
     * @param Request $request
     */
    public function newRESTAction(Request $request)
    {
        try {
            $issueArray = json_decode($request->get('issue'));

            $em = $this->get('doctrine.orm.entity_manager');

            $issue = new Issue();
            $issue->setId('XXX');
            $issue->setNumber($issueArray->number);
            $issue->setTitle($issueArray->title);
            $issue->setContent($issueArray->content);

            $category = $em->getRepository('AppBundle:Category')->find($issueArray->category);
            $issue->setCategory($category);
            $status = $em->getRepository('AppBundle:IssueStatus')->find($issueArray->status);
            $issue->setStatus($status);
            $priority = $em->getRepository('AppBundle:IssuePriority')->find($issueArray->priority);
            $issue->setPriority($priority);
            $type = $em->getRepository('AppBundle:IssueType')->find($issueArray->type);
            $issue->setType($type);

            $reportedBy = $em->getRepository('AppBundle:User')->find($issueArray->reportedBy);
            $issue->setReportedBy($reportedBy);
            $assignedTo = $em->getRepository('AppBundle:User')->find($issueArray->assignedTo);
            $issue->setAssignedTo($assignedTo);

            $issue->setUpdatedAt($issueArray->updatedAt ? new \DateTime($issueArray->updatedAt) : null);
            $issue->setReceivedAt($issueArray->receivedAt ? new \DateTime($issueArray->receivedAt) : null);
            $issue->setDeadlineAt($issueArray->deadlineAt ? new \DateTime($issueArray->deadlineAt) : null);
            $issue->setCreatedAt($issueArray->createdAt ? new \DateTime($issueArray->createdAt) : null);

            if ($issueArray->createdBy) {
                $createdBy = $em->getRepository('AppBundle:User')->find($issueArray->createdBy);
            } else {
                $createdBy = null;
            }

            $issue->setCreatedBy($createdBy);

            /*  if ($issueArray->createdBy){
                  $updatedBy = $em->getRepository('AppBundle:User')->find($issueArray->updatedBy);
              } else {
                  $updatedBy = null;
              }*/

            //$issue->setUpdatedBy($updatedBy);

            $issue->setProgress($issueArray->progress);
            $issue->setEstimatedHours($issueArray->estimatedHours);
            $issue->setActualHours($issueArray->actualHours);

            if ($issueArray->requirement) {
                $requirement = $em->getRepository('AppBundle:Requirement')->find($issueArray->requirement);
            } else {
                $requirement = null;
            }

            $issue->setRequirement($requirement);

            $em->persist($issue);

            $em->flush();

            $issue->setId('XXX');

            $em->persist($issue);

            $em->flush();
        } catch (\Exception $e) {
            dump($e->getTraceAsString());
            die;
        }
    }

    /**
     * @Route("issue/resttest", name="issue_resttest")
     * @Method({"GET"})
     *
     * @param Request $request
     */
    public function newRESTTESTAction(Request $request)
    {
        $e = new CurlRequestResponse();
        $e->login('http://issuetracker/app_dev.php/login_check', 'rei', '123');

        /**
         * @var Issue
         */
        $issue = $this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Issue')->find('20180922-034233-356910-5575C2E');

        $response = $e->navigate(
            'http://issuetracker/app_dev.php/issue/newrest', 'POST',
            ['issue' => json_encode($issue->serialize())]
        );

        // $e->exampleNavigate();
        return  new Response(($response['content']));
        // return new JsonResponse($request);
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
