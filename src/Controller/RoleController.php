<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\RoleType;
use App\Entity\Role;

class RoleController extends BaseController {
    
    public function listAction() {
        $this->get('breadcrumbs')
                ->add('Home', 'homepage')
                ->add('Roles and permissions');
        
        $collection = $this->getRepository('Role')
                ->getCollection()
                ->getQuery()
                ->getResult();
        
        return $this->render('roles/list.twig',
                array(
                    'title' => $this->trans('title.page.roles.list'),
                    'collection' => $collection
                )
        );
    }
    
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
    
    public function deleteAction($id) {
        
    }
    
}