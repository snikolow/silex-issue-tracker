<?php

if( isset($app) ) {
    $app['widgets'] = $app->share(function() use ($app) {
        return array(
            'navigation' => array(
                'class' => 'App\\Widget\\NavigationWidget',
            ),
            'topNavigation' => array(
                'class' => 'App\\Widget\\NavigationWidget'
            ),
            'sideNavigation' => array(
                'class' => 'App\\Widget\\SideNavigationWidget',
                'calls' => array(
                    'setAuthorizationChecker' => $app['security.authorization_checker']
                )
            ),
            'breadcrumbs' => array(
                'class' => 'App\\Widget\\BreadcrumbWidget',
                'calls' => array(
                    'setBreadcrumbService' => $app['breadcrumbs']
                )
            ),
            'flashes' => array(
                'class' => 'App\\Widget\\FlashWidget'
            )
        );
    });
}
