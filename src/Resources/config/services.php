<?php

use App\Service;

if( isset($app) ) {
    $app['breadcrumbs'] = $app->share(function() {
        return new Service\Breadcrumb();
    });
    
    $app['datetime'] = $app->share(function() {
        return new Service\DateTimeHelper();
    });
}
