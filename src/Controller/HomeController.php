<?php

namespace App\Controller;

class HomeController extends BaseController {

    public function indexAction() {
        $this->get('breadcrumbs')
            ->add('Home');
        
        $repository = $this->getRepository('Issue');
        
        $assignedIssues = $repository->getAssignedIssues($this->getUser());
        $createdIssues = $repository->getCreatedIssues($this->getUser());

        return $this->render('home/index.twig',
                array(
                    'assignedIssues'    => $assignedIssues,
                    'createdIssues'     => $createdIssues
                )
        );
    }

}
