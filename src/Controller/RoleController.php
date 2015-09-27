<?php

namespace Tracker\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tracker\Form\Type\RoleType;
use Tracker\Entity\Role;

class RoleController extends BaseController {
    
    /**
     * List roles
     * 
     * @param int $page
     */
    public function listAction($page = 1) {
        $this->get('breadcrumbs')
                ->add('Home', 'homepage')
                ->add('Roles and permissions');
        
        $query = $this->getRepository('Role')->getCollection();
        $paginator = $this->get('paginator')->paginate($query, $page, 10);
        
        return $this->render('roles/list.twig',
                array(
                    'title' => $this->trans('title.page.roles.list'),
                    'collection' => $paginator
                )
        );
    }
    
    /**
     * Create role
     * 
     * @param Request $request
     */
    public function createAction(Request $request) {
        $this->get('breadcrumbs')
                ->add('Home', 'homepage')
                ->add('Roles and permissions', 'roles_list')
                ->add('Manage role');
        
        $form = $this->get('form.factory')->create(new RoleType(), new Role());
        
        if( $form->handleRequest($request)->isValid() ) {
            $entity = $form->getData();
            
            $this->persistAndFlush($entity);
            
            $this->addFlash('sucess', 'Role added successfuly!');
            
            return $this->redirectToRoute('roles_list');
        }
        
        return $this->render('roles/form.twig',
                array(
                    'title' => $this->trans('title.page.roles.create'),
                    'form' => $form->createView()
                )
        );
    }
    
    /**
     * Update existing role
     * 
     * @param Request $request
     * @param int $id
     */
    public function updateAction(Request $request, $id) {
        $this->get('breadcrumbs')
                ->add('Home', 'homepage')
                ->add('Roles and permissions', 'roles_list')
                ->add('Manage role');
        
        if( ! $role = $this->getRepository('Role')->find($id) ) {
            $this->application->abort(404, 'Requested role not found!');
        }
        
        $form = $this->get('form.factory')->create(new RoleType(), $role);
        
        if( $form->handleRequest($request)->isValid() ) {
            $entity = $form->getData();
            
            $this->persistAndFlush($entity);
            
            $this->addFlash('success', 'Role updated successfuly!');
            
            return $this->redirectToRoute('roles_list');
        }
        
        return $this->render('roles/form.twig',
                array(
                    'title' => $this->trans('title.page.roles.update'),
                    'form' => $form->createView()
                )
        );
    }
    
    /**
     * Delete role
     * 
     * @param int $id
     */
    public function deleteAction($id) {
        
    }
    
}