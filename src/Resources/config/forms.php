<?php

use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;

if( isset($app) ) {
    $app['form.extensions'] = $app->share($app->extend('form.extensions', function ($extensions, $app) {
        $manager = $app['managerRegistry'];
        $extensions[] = new DoctrineOrmExtension($manager);

        return $extensions;
    }));
}