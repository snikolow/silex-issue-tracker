<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\ProjectType;
use App\Entity\Project;

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
                    'form' => $form->createView()
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
        
        if( ! $project = $this->getRepository('Project')->find($id) ) {
            $this->application->abort(404, 'The request project was not found!');
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
                    'form' => $form->createView()
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
    
}