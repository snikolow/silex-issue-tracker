<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\Type\UserType;
use App\Entity\User;

class UserController extends BaseController {
    
    /**
     * List available users
     * @param  integer         $page
     */
    public function listAction($page = 1) {
        $this->get('breadcrumbs')
            ->add('Home', 'homepage')
            ->add('Users');
        
        $collection = $this->getRepository('User')
                ->getCollection()
                ->getQuery()
                ->getResult();

        return $this->render('users/list.twig',
            array(
                'collection' => $collection,
                'title' => $this->trans('title.page.users.list')
            )
        );
    }

    /**
     * Create new user
     *
     * @param  Request         $request
     */
    public function createAction(Request $request) {
        $this->get('breadcrumbs')
            ->add('Home', 'homepage')
            ->add('Users', 'users_list')
            ->add('Manage user');
        
        $form = $this->get('form.factory')->create(new UserType(), new User(), array('create' => true));

        if( $form->handleRequest($request)->isValid() ) {
            $entity = $form->getData();
            $plainPassword = $entity->getPassword();
            
            $entity->setPassword( $this->encodePassword($entity, $plainPassword) );

            $this->persistAndFlush($entity);

            $this->addFlash('success', 'User created successfuly!');

            return $this->redirectToRoute('users_list');
        }

        return $this->render('users/form.twig',
            array(
                'form' => $form->createView(),
                'title' => $this->trans('title.page.users.create')
            )
        );
    }

    /**
     * Update existing user.
     *
     * @param  Request         $request
     * @param  integer         $id
     */
    public function updateAction(Request $request, $id) {
        $this->get('breadcrumbs')
            ->add('Home', 'homepage')
            ->add('Users', 'users_list')
            ->add('Manage user');

        if( ! $user = $this->getRepository('User')->find($id) ) {
            $this->abort(404, 'Requested user not found!');
        }

        $form = $this->get('form.factory')->create(new UserType(), $user);

        if( $form->handleRequest($request)->isValid() ) {
            $entity = $form->getData();

            $this->persistAndFlush($entity);

            $this->addFlash('success', 'User updated successfuly!');

            return $this->redirectToRoute('users_list');
        }

        return $this->render('users/form.twig',
            array(
                'form' => $form->createView(),
                'title' => $this->trans('title.page.users.update')
            )
        );
    }

    /**
     * Delete user.
     *
     * @param  integer        $id
     */
    public function deleteAction($id) {
        if( $entity = $this->getRepository('User')->find($id) ) {
            $this->removeAndFlush($entity);
        }

        $this->addFlash('success', 'User deleted successfuly!');

        return $this->redirectToRoute('users_list');
    }
    
    public function ajaxAutocompleteAction(Request $request) {
        $response = array();
        
        if( $request->isXmlHttpRequest() ) {
            $term = filter_var($request->get('q'), FILTER_SANITIZE_STRING);
            
            $result = $this->getRepository('User')->findUsersByKeyword($term);
            
            if( count($result) ) {
                $response['query'] = $term;
                
                foreach($result as $user) {
                    $response['suggestions'][] = array(
                        'value' => sprintf('%s (%s)', $user['name'], $user['email']),
                        'name'  => $user['name'],
                        'data'  => $user['id']
                    );
                }
            }
        }
        
        return new JsonResponse($response);
    }
    
}
