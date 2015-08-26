<?php

use Silex\Provider;
use App\Component\Security\Provider\UserProvider;

if( isset($app) ) {
    /** @var $app \Silex\Application */

    /**
     * Register twig engine
     *
     * twig.path should not be obtained from configuration, like cache path,
     * since the following directory path is used when resource paths are
     * being loaded/registered.
     */
    $app->register(new Provider\TwigServiceProvider(), array(
        'twig.path'             => ROOT_PATH . '/src/Resources/views',
        'twig.options'          => array(
            'cache'             => $app['project.config']['cache_path']
        ),
        'twig.form.templates'   => array(
            /**
             * Register our form theme files.
             * Since twig.form.templates is an array defined
             * in TwigServiceProvider, we override it here,
             * but adding again "form_div_layout.html.twig" is necessary
             * as we use it as a base.
             *
             * bootstrap_theme.twig is originally copied from Symfony's core files,
             * as it's added since @2.6
             * https://github.com/symfony/TwigBridge/blob/master/Resources/views/Form/bootstrap_3_layout.html.twig
             */
            'form_div_layout.html.twig',
            'layouts/theme/bootstrap_theme.twig'
        )
    ));

    // Register URL generator provider
    $app->register(new Provider\UrlGeneratorServiceProvider());

    // Register Http cache provider 
    $app->register(new Provider\HttpCacheServiceProvider(), array(
        'http_cache.cache_dir'  => $app['project.config']['cache_path']
    ));

    // Register translator provider
    $app->register(new Provider\TranslationServiceProvider(), array(
        'locale' => $app['project.config']['locale'],
        'locale_fallbacks'  => isset($app['project.config']['locale_fallbacks']) ? $app['project.config']['locale_fallbacks'] : array()
    ));
    
    $app->register(new Provider\DoctrineServiceProvider(), array(
        'db.options' => array(
            'driver'    => $app['project.config']['database']['driver'],
            'host'      => $app['project.config']['database']['host'],
            'dbname'    => $app['project.config']['database']['name'],
            'user'      => $app['project.config']['database']['user'],
            'password'  => $app['project.config']['database']['pass'],
            'charset'   => $app['project.config']['database']['charset']
        )
    ));
    
    $app->register(new Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider(), array(
        'orm.proxies_dir' => $app['project.config']['cache_path'],
        'orm.em.options' => array(
            'mappings' => array(
                array(
                    'type' => 'annotation',
                    'namespace' => 'App\Entity',
                    'path' => ROOT_PATH . '/src/Entity'
                )
            )
        )
    ));
    
    $app->register(new App\Provider\ConsoleProvider\ConsoleServiceProvider());

    // Register Session service provider
    $app->register(new Provider\SessionServiceProvider());

    // Register Form provider
    $app->register(new Provider\FormServiceProvider());

    // Register Validator provider
    $app->register(new Provider\ValidatorServiceProvider());

    // Register Security service provider
    $app->register(new Provider\SecurityServiceProvider(), array(
        'security.firewalls' => array(
            'unsecured' => array(
                'pattern'   => '^/auth$',
                'anonymous' => true
            ),
            'application'   => array(
                'pattern'   => '^/',
                'form'      => array(
                    'login_path'    => '/auth',
                    'check_path'    => '/auth_check'
                ),
                'logout'    => array(
                    'logout_path'   => '/logout'
                ),
                'users'     => $app->share(function() use ($app) {
                    return new UserProvider(
                        $app['orm.em']->getRepository('App\Entity\User')
                    );
                })
            ),
        ),
        'security.role_hierarchy' => array(
            'ROLE_DEVELOPER'    => array('ROLE_ADMIN', 'ROLE_ALLOWED_TO_SWITCH'),
            'ROLE_ADMIN'        => array('ROLE_USER')
        )
    ));
}
