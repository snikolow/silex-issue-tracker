<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;

if( isset($app) && $app ) {
    $app['routes'] = $app->extend('routes', function(RouteCollection $collection) {
        $locator = new FileLocator(ROOT_PATH . '/src/Resources/config/routes');
        $loader = new YamlFileLoader($locator);
        $routes = $loader->load('main.yml');
        
        $collection->addCollection($routes);
        
        return $collection;
    });
}
