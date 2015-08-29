<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\TrackerType;
use App\Entity\Tracker;

class TrackerController extends BaseController {

    /**
     * List available trackers
     *
     * @param  integer        $page
     */
    public function listAction($page = 1) {
        $this->get('breadcrumbs')
            ->add('Home', 'homepage')
            ->add('Trackers');
        
        $query = $this->getRepository('Tracker')->getCollection();
        $paginator = $this->get('paginator')->paginate($query, $page, 10);

        return $this->render('trackers/list.twig',
            array(
                'collection' => $paginator,
                'title' => $this->trans('title.page.trackers.list')
            )
        );
    }

    /**
     * Create new tracker
     *
     * @param  Request        $request
     */
    public function createAction(Request $request) {
        $this->get('breadcrumbs')
            ->add('Home', 'homepage')
            ->add('Trackers', 'trackers_list')
            ->add('Manage tracker');
        
        $form = $this->get('form.factory')->create(new TrackerType(), new Tracker());

        if( $form->handleRequest($request)->isValid() ) {
            $entity = $form->getData();

            $this->persistAndFlush($entity);

            $this->addFlash('success', 'Tracker created successfuly!');

            return $this->redirectToRoute('trackers_list');
        }

        return $this->render('trackers/form.twig',
            array(
                'form' => $form->createView(),
                'title' => $this->trans('title.page.trackers.create')
            )
        );
    }

    /**
     * Update existing tracker
     *
     * @param  Request        $request
     * @param  int            $id
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function updateAction(Request $request, $id) {
        $this->get('breadcrumbs')
            ->add('Home', 'homepage')
            ->add('Trackers', 'trackers_list')
            ->add('Manage tracker');

        if( ! $tracker = $this->getRepository('Tracker')->find($id) ) {
            $this->application->abort(404, 'Requested tracker does not exists!');
        }

        $form = $this->get('form.factory')->create(new TrackerType(), $tracker);

        if( $form->handleRequest($request)->isValid() ) {
            $entity = $form->getData();

            $this->persistAndFlush($entity);

            $this->addFlash('success', 'Tracker updated successfuly!');

            return $this->redirectToRoute('trackers_list');
        }

        return $this->render('trackers/form.twig',
            array(
                'form' => $form->createView(),
                'title' => $this->trans('title.page.trackers.update')
            )
        );
    }

    /**
     * Delete tracker.
     *
     * @param  integer        $id
     */
    public function deleteAction($id) {
        if( $entity = $this->getRepository('Tracker')->find($id) ) {
            $this->removeAndFlush($entity);
        }

        $this->addFlash('success', 'Tracker deleted successfuly!');

        return $this->redirectToRoute('trackers_list');
    }

}
