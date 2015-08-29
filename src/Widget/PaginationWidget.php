<?php

namespace App\Widget;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginationWidget extends AbstractWidget {
    
    /** @var Request */
    private $request;
    
    /**
     * 
     * @param RequestStack $requestStack
     */
    public function setRequest(RequestStack $requestStack) {
        $this->request = $requestStack->getCurrentRequest();
    }
    
    public function getContent() {
        /* @var $paginator \App\Service\Paginator */
        $paginator = $this->params['paginator'];
        
        return $this->render('widget/pagination.twig',
                array(
                    'paginator' => $paginator
                )
        );
    }
    
}