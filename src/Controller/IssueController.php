<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\Type\IssueType;
use App\Entity\Issue;

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
        
        if( ! $this->isGranted('view', $project) ) {
            $this->application->abort(403, 'You are not a member of this project!');
        }
        
        $this->get('breadcrumbs')
                ->add('Home', 'homepage')
                ->add('Projects', 'projects_list')
                ->add($project->getTitle())
                ->add('Issues');
        
        $collection = $this->getRepository('Issue')->getCollectionByProject($project)
                ->getQuery()
                ->getResult();
        
        return $this->render('issues/list.twig',
                array(
                    'title' => $this->trans('title.page.issues.list'),
                    'collection' => $collection,
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
                    'title' => '',
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
        
        $this->get('breadcrumbs')
                ->add('Home', 'homepage')
                ->add('Projects', 'projects_list')
                ->add($project->getTitle())
                ->add('Issues', 'issues_list', array('identifier' => $project->getIdentifier()))
                ->add($issue->getSubject());
        
        $form = $this->get('form.factory')->create(new IssueType(), $issue, 
                array(
                    'action' => $this->url('issues_edit', array('id' => $id))
                )
        );
        
        if( $form->handleRequest($request)->isValid() ) {
            $entity = $form->getData();
            
            $this->persistAndFlush($entity);
            
            $this->addFlash('success', 'Issue updated successfuly!');
        }
        
        return $this->render('issues/details.twig',
                array(
                    'title' => '',
                    'object' => $issue,
                    'form' => $form->createView()
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
    
}