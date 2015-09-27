<?php

namespace Tracker\Event\Listener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Tracker\Controller\Definition\BeforeActionInterface;

class BeforeActionListener {

    /**
     *
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event) {
        $controllerData = $event->getController();

        // If no controller is found, $controllerData should not be an array.
        // Therefore, return and don't execute the rest of the code.
        if( ! is_array($controllerData) ) {
            return ;
        }

        // $controller is an instance of the requested Controller
        $controller = $controllerData[0];
        $action = $controllerData[1];

        if( $controller instanceof BeforeActionInterface ) {
            $exceptActions = array();

            if( property_exists($controller, 'exceptActions') && is_array($controller->exceptActions) ) {
                $exceptActions = $controller->exceptActions;
            }

            if( ! in_array($action, $exceptActions) ) {
                $controller->beforeFilter();
            }
        }
    }

}
