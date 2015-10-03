<?php

namespace Tracker\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tracker\Form\Type\IssueType;
use Tracker\Form\Type\CommentType;
use Tracker\Entity\Issue;
use Tracker\Entity\Comment;

class IssueController extends BaseController {

    /**
     * List issues
     *
     * @param string $identifier
     * @param int $page
     */
    public function listAction($identifier, $page = 1) {
        if( ! $project = $this->getRepository('Project')->findOneByIdentifier($identifier) ) {
            $this->application->abort(404, 'Project not found!');
        }

        $this->denyAccessUnlessGranted('view', $project, 'You are not a member of this project!');

        $this->get('breadcrumbs')
                ->add('Home', 'homepage')
                ->add('Projects', 'projects_list')
                ->add($project->getTitle())
                ->add('Issues');

        $query = $this->getRepository('Issue')->getCollectionByProject($project);
        $paginator = $this->get('paginator')->paginate($query, $page, 25);

        return $this->render('issues/list.twig',
                array(
                    'title' => $this->trans('title.page.issues.list'),
                    'collection' => $paginator,
                    'project' => $project
                )
        );
    }

    /**
     * Create issue
     *
     * @param Request $request
     * @param string $identifier
     */
    public function createAction(Request $request, $identifier) {
        if( ! $project = $this->getRepository('Project')->findOneByIdentifier($identifier) ) {
            $this->application->abort(404, 'Project not found!');
        }

        $this->denyAccessUnlessGranted('view', $project, 'You are not a member of this project!');

        $this->get('breadcrumbs')
                ->add('Home', 'homepage')
                ->add('Projects', 'projects_list')
                ->add($project->getTitle())
                ->add('Issues')
                ->add('Manage issue');

        $entity = new Issue();
        $entity->setCreatedBy($this->getUser());
        $entity->setProject($project);

        $form = $this->get('form.factory')->create(new IssueType(), $entity);

        if( $form->handleRequest($request)->isValid() ) {
            $entity = $form->getData();

            $this->persistAndFlush($entity);

            $this->addFlash('success', 'Issue created successfuly!');

            return $this->redirectToRoute('issues_list', array('identifier' => $project->getIdentifier()));
        }

        return $this->render('issues/create.twig',
                array(
                    'title' => $this->trans('title.page.issues.create'),
                    'form' => $form->createView()
                )
        );
    }

    /**
     * Update issue
     *
     * @param Request $request
     * @param int $id
     */
    public function updateAction(Request $request, $id) {
        if( ! $issue = $this->getRepository('Issue')->find($id) ) {
            $this->application->abort(404, 'Issue not found');
        }

        $project = $issue->getProject();

        $this->denyAccessUnlessGranted('view', $project, 'You are not a member of this project!');
        $this->denyAccessUnlessGranted('view', $issue, 'You are not allowed to view this issue!');

        $this->get('breadcrumbs')
                ->add('Home', 'homepage')
                ->add('Projects', 'projects_list')
                ->add($project->getTitle())
                ->add('Issues', 'issues_list', array('identifier' => $project->getIdentifier()))
                ->add($issue->getSubject());

        // Create the form for updating this issue
        $form = $this->get('form.factory')->create(new IssueType(), $issue,
                array(
                    'action' => $this->url('issues_edit', array('id' => $id))
                )
        );
        $form->handleRequest($request);

        // Check if our update form is submitted, and persist the changes.
        if( $form->isSubmitted() && $form->isValid() ) {
            $entity = $form->getData();

            $this->persistAndFlush($entity);

            $this->addFlash('success', 'Issue updated successfuly!');
        }

        // reate the form for commeting on a issue
        $commentForm = $this->get('form.factory')->create(new CommentType(), new Comment());
        $commentForm->handleRequest($request);

        // Check if our comment form is submitted, and add the comment.
        if( $commentForm->isSubmitted() && $commentForm->isValid() ) {
            $comment = $commentForm->getData();
            $comment->setIssue($issue);
            $comment->setMember($this->getUser());

            $this->persistAndFlush($comment);

            $this->addFlash('success', 'Comment added successfuly!');
        }

        // Get all comments related to this issue.
        $comments = $this->getRepository('Comment')->getIssueComments($issue);

        return $this->render('issues/details.twig',
                array(
                    'title' => '',
                    'object' => $issue,
                    'form' => $form->createView(),
                    'commentForm' => $commentForm->createView(),
                    'comments' => $comments
                )
        );
    }

    public function ajaxUpdateAction(Request $request) {
        $form = $this->get('form.factory')->create(new IssueType(), new Issue());
        $request->request->remove('_wysihtml5_mode');

        if( $form->handleRequest($request)->isValid() ) {
            $response['isValid'] = true;
        }
        else {
            $response['isValid'] = false;
            $response['errors'] = $this->getFormErrors($form);
        }

        return new JsonResponse($response);
    }

    /**
     * Delete issue
     *
     * @param int $id
     */
    public function deleteAction($id) {

    }
    
    /**
     * Delete a comment
     * 
     * @param int $id
     */
    public function deleteCommentAction($id) {
        /* @var $comment \Tracker\Entity\Comment */
        if( ! $comment = $this->getManager()->find('Tracker\Entity\Comment', $id) ) {
            $this->application->abort(404, 'Comment not found!');
        }
        
        $issue = $comment->getIssue();
        
        $this->denyAccessUnlessGranted('delete', $comment);
        
        $this->removeAndFlush($comment);
        
        return $this->redirectToRoute('issues_edit', array('id' => $issue->getId()));
    }

}
