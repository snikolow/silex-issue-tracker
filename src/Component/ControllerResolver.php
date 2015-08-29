<?php

namespace App\Component;

use Silex\ControllerResolver as BaseControllerResolver;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\BaseController;

class ControllerResolver extends BaseControllerResolver {

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Controller\BaseController $controller
     * @param array $parameters
     */
    protected function doGetArguments(Request $request, $controller, array $parameters)
    {
        /**
         * If our requested controller is a child of BaseController
         * then we inject an instance of Application, so that
         * it can be easily accessed in every method.
         */
        if( is_array($controller) && isset($controller[0]) && $controller[0] instanceof BaseController ) {
            $controller[0]->setApplication($this->app);
        }

        return parent::doGetArguments($request, $controller, $parameters);
    }

}
