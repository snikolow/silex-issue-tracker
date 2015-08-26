<?php

if( isset($app) ) {
    $app['project.config'] = $app->share(function($app) {
        return array(
            // Absolute path to where cache should be written
            'cache_path'        => ROOT_PATH . '/var/cache',
            // Default application locale
            'locale'            => 'en',
            // Database settings
            'database'          => array(
                'driver' => 'pdo_mysql',
                'host'  => 'localhost',
                'user'  => 'root',
                'pass'  => 'dbpassword',
                'name'  => 'test',
                'charset' => 'utf8'
            )
        );
    });
}
