<?php

namespace App\Component;

use Silex\ControllerResolver as BaseControllerResolver;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\BaseController;

class ControllerResolver extends BaseControllerResolver {

    /**
     * Override default method functionally so that we can implement
     * custom dependency injector.
     *
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

        /**
         * From here on its pretty much copied from the original ControllerResolver class.
         */
        $attributes = $request->attributes->all();
        $arguments = array();
        foreach ($parameters as $param) {
            if ( array_key_exists($param->name, $attributes) ) {
                $arguments[] = $attributes[$param->name];
            }
            elseif ( $param->getClass() && $param->getClass()->isInstance($request) ) {
                $arguments[] = $request;
            }
            elseif ( $param->isDefaultValueAvailable() ) {
                $arguments[] = $param->getDefaultValue();
            }
            elseif ( $param->getClass()->isSubclassOf('App\Repository\AbstractRepository') ) {
                /**
                 * If requested parameter is implementing the following interface
                 * create a new instance and return it.
                 *
                 * Since $param->getClass() returns an object of type ReflectionClass,
                 * we can directly access and use newInstance() method.
                 *
                 * http://php.net/manual/en/class.reflectionclass.php
                 */
                $object = $param->getClass()->newInstance($this->app['pdo']);

                $arguments[] = $object;
            }
            else {
                if (is_array($controller)) {
                    $repr = sprintf('%s::%s()', get_class($controller[0]), $controller[1]);
                } elseif (is_object($controller)) {
                    $repr = get_class($controller);
                } else {
                    $repr = $controller;
                }

                throw new \RuntimeException(
                    sprintf(
                        'Controller "%s" requires that you provide a value for the "$%s" argument (because there is no default value or because there is a non optional argument after this one).'
                        , $repr
                        , $param->name
                    )
                );
            }
        }

        return $arguments;
    }

}
