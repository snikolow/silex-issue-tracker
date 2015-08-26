<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\PriorityType;
use App\Entity\Priority;

class PriorityController extends BaseController {

    /**
     * List available priorities
     * @param  integer         $page
     */
    public function listAction($page = 1) {
        $this->get('breadcrumbs')
            ->add('Home', 'homepage')
            ->add('Priorities');
        
        $collection = $this->getRepository('Priority')
                ->getCollection()
                ->getQuery()
                ->getResult();

        return $this->render('priorities/list.twig',
            array(
                'collection' => $collection,
                'title' => $this->trans('title.page.priorities.list')
            )
        );
    }

    /**
     * Create new priority
     *
     * @param  Request         $request
     */
    public function createAction(Request $request) {
        $this->get('breadcrumbs')
            ->add('Home', 'homepage')
            ->add('Priorities', 'priorities_list')
            ->add('Manage priority');
        
        $form = $this->get('form.factory')->create(new PriorityType(), new Priority());

        if( $form->handleRequest($request)->isValid() ) {
            $entity = $form->getData();

            $this->persistAndFlush($entity);

            $this->addFlash('success', 'Priority created successfuly!');

            return $this->redirectToRoute('priorities_list');
        }

        return $this->render('priorities/form.twig',
            array(
                'form' => $form->createView(),
                'title' => $this->trans('title.page.priorities.create')
            )
        );
    }

    /**
     * Update existing priority.
     *
     * @param  Request         $request
     * @param  integer         $id
     */
    public function updateAction(Request $request, $id) {
        $this->get('breadcrumbs')
            ->add('Home', 'homepage')
            ->add('Priorities', 'priorities_list')
            ->add('Manage priority');

        if( ! $priority = $this->getRepository('Priority')->find($id) ) {
            $this->abort(404, 'Requested priority not found!');
        }

        $form = $this->get('form.factory')->create(new PriorityType(), $priority);

        if( $form->handleRequest($request)->isValid() ) {
            $entity = $form->getData();

            $this->persistAndFlush($entity);

            $this->addFlash('success', 'Priority updated successfuly!');

            return $this->redirectToRoute('priorities_list');
        }

        return $this->render('priorities/form.twig',
            array(
                'form' => $form->createView(),
                'title' => $this->trans('title.page.priorities.update')
            )
        );
    }

    /**
     * Delete priority.
     *
     * @param  integer        $id
     */
    public function deleteAction($id) {
        if( $entity = $this->getRepository('Priority')->find($id) ) {
            $this->removeAndFlush($entity);
        }

        $this->addFlash('success', 'Priority deleted successfuly!');

        return $this->redirectToRoute('priorities_list');
    }

}
