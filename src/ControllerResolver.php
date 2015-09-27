<?php

namespace Tracker;

use Silex\ControllerResolver as BaseControllerResolver;
use App\Controller\BaseController;

class ControllerResolver extends BaseControllerResolver {

    /**
     *
     * @param string $class
     * @return BaseController
     */
    protected function instantiateController($class)
    {
        $reflection = new \ReflectionClass($class);

        if( $reflection->isSubclassOf('Tracker\Controller\BaseController') ) {
            return $reflection->newInstance($this->app);
        }

        return $reflection->newInstance();
    }

}
