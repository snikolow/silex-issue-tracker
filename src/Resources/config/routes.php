<?php

use Symfony\Component\Yaml\Yaml;

if( isset($app) && $app ) {
    // Global configuration for our controller. Make sure that every route
    // that contains an {id} or {page} dynamic parameters are valid
    // integer numbers. {page} should also automatically receive a default
    // value of 1.
    $app['controllers']
        ->assert('id', '\d+')
        ->assert('page', '\d+')
        ->value('page', 1)
    ;

    $routes = (Yaml::parse(file_get_contents(__DIR__ . '/routes/routing.yml')));

    if( count($routes) ) {
        foreach($routes as $name => $item) {
            /**
             * Restrict current route for specific http request.
             * If option "method" is not provided, we proceed by default
             * with calling $app->get(), otherwise we would use
             * $app->match() combined with provided methods.
             *
             * This option could be either a string or array and it's optional.
             *
             * route_name:
             *     method: PUT|POST
             *
             * route_name:
             *     - PUT
             *     - POST
             */
            if( isset($item['method']) ) {
                $object = $app->match($item['route'], $item['handler']);
                
                $method = $item['method'];

                if( is_array($method) ) {
                    $method = join('|', $method);
                }

                $object->method($method);
            }
            else {
                $object = $app->get($item['route'], $item['handler']);
            }

            /**
             *  Normally, the key we receive for each array item is being
             *  used as route name. If "bind" parameter is present, then we
             *  shall use it instead.
             *
             *  "bind" parameter is optional here.
             */
            if( isset($item['bind']) ) {
                $name = $item['bind'];
            }

            $object->bind($name);

            /**
             * Apply default values for dynamic parameters. It's useful for instance
             * to add default value for routes where pagination is being used.
             * This application is applying default values for every route that contains
             * "page" parameter with value of 1.
             *
             * This parameter is optional and it should be an array if given.
             *
             * route_name:
             *     route: /projects/{page}
             *     defaults:
             *         page: 1
             */
            if( isset($item['defaults']) && is_array($item['defaults']) ) {
                foreach($item['defaults'] as $option => $value) {
                    $object->value($option, $value);
                }
            }

            /**
             * Test dynamic parameter with given reg.ex.
             * This application is applying default reg.ex. check for every
             * route that contains "id" parameter.
             *
             * This parameter is optional and it should be an array if given.
             *
             * route_name:
             *     route: /project/{id}
             *     assert:
             *         id: \d+
             */
            if( isset($item['assert']) && is_array($item['assert']) ) {
                foreach($item['assert'] as $option => $expr) {
                    $object->assert($option, $expr);
                }
            }
            
            /**
             * Restrict current route for specific role.
             * Defined roles can be found in providers.php
             */
            if( isset($item['only']) ) {
                $object->before(function() use ($app, $item) {
                    if( ! $app['security.authorization_checker']->isGranted($item['only']) ) {
                        $app->abort(403, 'Access denied!');
                    }
                });
            }
        }
    }
}
