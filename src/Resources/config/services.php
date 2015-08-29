<?php

use App\Service;
use App\Component\Doctrine\Common\Persistence\ManagerRegistry;

if( isset($app) ) {
    $app['breadcrumbs'] = $app->share(function() {
        return new Service\Breadcrumb();
    });
    
    $app['datetime'] = $app->share(function() {
        return new Service\DateTimeHelper();
    });
    
    $app['paginator'] = $app->share(function() {
        return new Service\Paginator();
    });
    
    $app['managerRegistry'] = $app->share(function() use ($app) {
        $manager = new ManagerRegistry(
            null, array(), array('orm.em'), null, 'orm.em', '\Doctrine\ORM\Proxy\Proxy'
        );
        
        $manager->setContainer($app);
        
        return $manager;
    });
}
