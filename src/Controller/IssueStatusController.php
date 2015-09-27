<?php

namespace Tracker\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tracker\Form\Type\IssueStatusType;
use Tracker\Entity\IssueStatus;

class IssueStatusController extends BaseController {

    /**
     * List available statuses
     * @param  integer         $page
     */
    public function listAction($page = 1) {
        $this->get('breadcrumbs')
            ->add('Home', 'homepage')
            ->add('Issue statuses');
        
        $query = $this->getRepository('IssueStatus')->getCollection();
        $paginator = $this->get('paginator')->paginate($query, $page, 10);

        return $this->render('statuses/list.twig',
            array(
                'collection' => $paginator,
                'title' => $this->trans('title.page.statuses.list')
            )
        );
    }

    /**
     * Create new status
     *
     * @param  Request         $request
     */
    public function createAction(Request $request) {
        $this->get('breadcrumbs')
            ->add('Home', 'homepage')
            ->add('Statuses', 'statuses_list')
            ->add('Manage status');
        $form = $this->get('form.factory')->create(new IssueStatusType(), new IssueStatus());

        if( $form->handleRequest($request)->isValid() ) {
            $entity = $form->getData();

            $this->persistAndFlush($entity);

            $this->addFlash('success', 'Status created successfuly!');

            return $this->redirectToRoute('statuses_list');
        }

        return $this->render('statuses/form.twig',
            array(
                'form' => $form->createView(),
                'title' => $this->trans('title.page.statuses.create')
            )
        );
    }

    /**
     * Update existing status.
     *
     * @param  Request         $request
     * @param  integer         $id
     */
    public function updateAction(Request $request, $id) {
        $this->get('breadcrumbs')
            ->add('Home', 'homepage')
            ->add('Statuses', 'statuses_list')
            ->add('Manage status');
        
        if( ! $status = $this->getRepository('IssueStatus')->find($id) ) {
            $this->abort(404, 'Requested status not found!');
        }

        $form = $this->get('form.factory')->create(new IssueStatusType(), $status);

        if( $form->handleRequest($request)->isValid() ) {
            $entity = $form->getData();

            $this->persistAndFlush($entity);

            $this->addFlash('success', 'Status updated successfuly!');

            return $this->redirectToRoute('statuses_list');
        }

        return $this->render('statuses/form.twig',
            array(
                'form' => $form->createView(),
                'title' => $this->trans('title.page.statuses.update')
            )
        );
    }

    /**
     * Delete status.
     *
     * @param  integer        $id
     */
    public function deleteAction($id) {
        if( $entity = $this->getRepository('IssueStatus')->find($id) ) {
            $this->removeAndFlush($entity);
        }

        $this->addFlash('success', 'Status deleted successfuly!');

        return $this->redirectToRoute('statuses_list');
    }

}
