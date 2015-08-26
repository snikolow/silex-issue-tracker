<?php

use \App\Component\Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;

if( isset($app) ) {
    $app['form.extensions'] = $app->share($app->extend('form.extensions', function ($extensions, $app) {
        $manager = new ManagerRegistry(
            null, array(), array('orm.em'), null, null, '\Doctrine\ORM\Proxy\Proxy'
        );
        
        $manager->setContainer($app);
        $extensions[] = new DoctrineOrmExtension($manager);

        return $extensions;
    }));
}