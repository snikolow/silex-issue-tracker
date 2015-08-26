<?php

use App\Twig\Extension\AppExtension;

/**
 * Add something like a fallback functionality for our templates.
 * Since TwigServiceProvider accepted /App/Resources/views/
 * as main template directory, at least one theme directory
 * needs to be added. In this case, that would be - /default/.
 * Every original template shall be saved there. There will also be
 * provided a option for a user to define his own theme directory,
 * so that it would be possible to override any template if needed.
 */
$app['twig.loader.filesystem'] = $app->share(
    $app->extend('twig.loader.filesystem', function($loader, $app) {
        /**
         * Check if there is any user-defined theme directory
         * and add it to our resources path. Registering /default/ here is mandatory.
         * Since Twig looks for the requested template in the way our
         * resources path are added, first we should register our
         * user-defined directory if present.
         */
        if( isset($app['project.config']['theme']) ) {
            $loader->addPath(ROOT_PATH . '/src/Resources/views/' . trim($app['project.config']['theme'], '/'));
        }

        $loader->addPath(ROOT_PATH . '/src/Resources/views/default');

        return $loader;
    })
);

/**
 * Register custom Twig extension that provides simple functionality,
 * like defining a asset() function, or widget() function created for
 * this project.
 */
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    /* @var $twig \Twig_Environment */
    $appExtension = new AppExtension(
            $app['request'],
            $app['datetime'],
            $app['widgets']
    );

    $twig->addExtension($appExtension);

    return $twig;
}));
