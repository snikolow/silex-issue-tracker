<?php

namespace Tracker\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tracker\Form\Type\ProjectType;
use Tracker\Entity\Project;
use Tracker\Entity\ProjectMember;

class ProjectController extends BaseController {
    
    /**
     * 
     * @param int $page
     */
    public function listAction($page = 1) {
        $this->get('breadcrumbs')
                ->add('Home', 'homepage')
                ->add('Projects');
        
        $query = $this->getRepository('Project')->getCollection($this->getUser());
        $paginator = $this->get('paginator')->paginate($query, $page, 10);
        
        return $this->render('projects/list.twig',
                array(
                    'title' => $this->trans('title.page.projects.list'),
                    'collection' => $paginator
                )
        );
    }
    
    /**
     * 
     * @param Request $request
     */
    public function createAction(Request $request) {
        $this->get('breadcrumbs')
                ->add('Home', 'homepage')
                ->add('Projects', 'projects_list')
                ->add('Manage project');
        
        $project = new Project();
        $project->setCreatedBy($this->getUser());
        
        $form = $this->get('form.factory')->create(new ProjectType(), $project);
        
        if( $form->handleRequest($request)->isValid() ) {
            $entity = $form->getData();
            
            $this->persistAndFlush($entity);
            
            $this->addFlash('success', 'Project created successfuly!');
            
            return $this->redirectToRoute('projects_list');
        }
        
        return $this->render('projects/form.twig',
                array(
                    'title' => $this->trans('title.page.projects.create'),
                    'form' => $form->createView(),
                    'project' => null
                )
        );
    }
    
    /**
     * 
     * @param Request $request
     * @param int $id
     */
    public function updateAction(Request $request, $id) {
        $this->get('breadcrumbs')
                ->add('Home', 'homepage')
                ->add('Projects', 'projects_list')
                ->add('Manage project');
        
        if( ! $project = $this->getRepository('Project')->findProjectById($id) ) {
            $this->application->abort(404, 'The requested project was not found!');
        }
        
        $form = $this->get('form.factory')->create(new ProjectType(), $project, array('edit' => true));
        
        if( $form->handleRequest($request)->isValid() ) {
            $entity = $form->getData();
            
            $this->persistAndFlush($entity);
            
            $this->addFlash('success', 'Project updated successfuly!');
            
            return $this->redirectToRoute('projects_list');
        }
        
        return $this->render('projects/form.twig',
                array(
                    'title' => $this->trans('title.page.projects.update'),
                    'form' => $form->createView(),
                    'project' => $project
                )
        );
    }
    
    /**
     * 
     * @param int $id
     */
    public function deleteAction($id) {
        if( $entity = $this->getRepository('Project')->find($id) ) {
            $this->removeAndFlush($entity);
        }
        
        $this->addFlash('success', 'Project deleted successfuly!');
        
        return $this->redirectToRoute('projects_list');
    }
    
    /**
     * Add selected member to a project.
     * 
     * @TODO: This piece of code should be moved to it's own
     * service file where all the magic should happen
     * and receive the response data only.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxAddMemberAction(Request $request) {
        $response = array();
        
        /* @var $project \Tracker\Entity\Project */
        $project = $this->getRepository('Project')->find(intval($request->get('id')));
        
        if( $project instanceof Project ) {
            $memberId = intval($request->get('memberId'));
            $rolesIds = array_map('intval', $request->get('rolesIds'));

            if( $memberId && count($rolesIds) ) {
                $user = $this->getRepository('User')->find($memberId);
                
                if( ! $this->getRepository('ProjectMember')->isAlreadyAMember($project, $user) ) {
                    $projectMember = new ProjectMember();
                    $projectMember->setProject($project);
                    $projectMember->setMember($user);
                    
                    $this->getManager()->persist($projectMember);
                    
                    foreach($rolesIds as $id) {
                        $role = $this->getManager()->find('Tracker\Entity\Role', $id);

                        $projectMember->getRoles()->add($role);
                    }
                    
                    $this->getManager()->persist($projectMember);

                    $this->getManager()->persist($project);
                    $response = array(
                        'success' => true,
                        'member' => $user->getName(),
                        'memberId' => $user->getId()
                    );
                }
                
                $this->getManager()->flush();
            }
            else {
                $response['message'] = 'Invalid member or roles!';
            }
        }
        else {
            $response['message'] = 'Project not found!';
        }
        
        return new JsonResponse($response);
    }
    
}