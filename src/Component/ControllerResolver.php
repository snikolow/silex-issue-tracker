<?php

namespace App\Component;

use Silex\ControllerResolver as BaseControllerResolver;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\BaseController;

class ControllerResolver extends BaseControllerResolver {
    
    /**
     * 
     * @param string $class
     * @return BaseController
     */
    protected function instantiateController($class)
    {
        $instance = new $class();
        
        if( $instance instanceof BaseController ) {
            $instance->setApplication($this->app);
        }
        
        return $instance;
    }

}
