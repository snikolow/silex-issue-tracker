<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\CategoryType;
use App\Entity\Category;

class CategoryController extends BaseController {

    /**
     * List available categories
     * @param  integer         $page
     */
    public function listAction($page = 1) {
        $this->get('breadcrumbs')
            ->add('Home', 'homepage')
            ->add('Priorities');
        
        $query = $this->getRepository('Category')->getCollection();
        $paginator = $this->get('paginator')->paginate($query, $page, 10);

        return $this->render('categories/list.twig',
            array(
                'collection' => $paginator,
                'title' => $this->trans('title.page.categories.list')
            )
        );
    }

    /**
     * Create new category
     *
     * @param  Request         $request
     */
    public function createAction(Request $request) {
        $this->get('breadcrumbs')
            ->add('Home', 'homepage')
            ->add('Priorities', 'categories_list')
            ->add('Manage category');
        
        $form = $this->get('form.factory')->create(new CategoryType(), new Category());

        if( $form->handleRequest($request)->isValid() ) {
            $entity = $form->getData();

            $this->persistAndFlush($entity);

            $this->addFlash('success', 'Category created successfuly!');

            return $this->redirectToRoute('categories_list');
        }

        return $this->render('categories/form.twig',
            array(
                'form' => $form->createView(),
                'title' => $this->trans('title.page.categories.create')
            )
        );
    }

    /**
     * Update existing category.
     *
     * @param  Request         $request
     * @param  integer         $id
     */
    public function updateAction(Request $request, $id) {
        $this->get('breadcrumbs')
            ->add('Home', 'homepage')
            ->add('Priorities', 'categories_list')
            ->add('Manage category');

        if( ! $category = $this->getRepository('Category')->find($id) ) {
            $this->abort(404, 'Requested category not found!');
        }

        $form = $this->get('form.factory')->create(new CategoryType(), $category);

        if( $form->handleRequest($request)->isValid() ) {
            $entity = $form->getData();

            $this->persistAndFlush($entity);

            $this->addFlash('success', 'Category updated successfuly!');

            return $this->redirectToRoute('categories_list');
        }

        return $this->render('categories/form.twig',
            array(
                'form' => $form->createView(),
                'title' => $this->trans('title.page.categories.update')
            )
        );
    }

    /**
     * Delete category.
     *
     * @param  integer        $id
     */
    public function deleteAction($id) {
        if( $entity = $this->getRepository('Category')->find($id) ) {
            $this->removeAndFlush($entity);
        }

        $this->addFlash('success', 'Category deleted successfuly!');

        return $this->redirectToRoute('categories_list');
    }

}
