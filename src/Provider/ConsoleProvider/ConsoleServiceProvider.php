<?php

namespace Tracker\Provider\ConsoleProvider;

use Silex\ServiceProviderInterface;
use Silex\Application;
use Tracker\Provider\ConsoleProvider\Console\ConsoleApplication;

class ConsoleServiceProvider implements ServiceProviderInterface {

    public function register(Application $app) {
        $app['console'] = $app->share(function() use ($app) {
            $application = new ConsoleApplication(
                    $app
            );

            return $application;
        });
    }

    public function boot(Application $app) {

    }

}
