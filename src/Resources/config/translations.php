<?php

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Translation\Loader\YamlFileLoader;

if( isset($app) ) {
    /**
     * If by any reason default locale is not present in configuration,
     * set it to be "en"
     */
    if( ! $locale = $app['project.config']['locale'] ) {
        $locale = 'en';
    }

    $app['translator.domains'] = array();

    /**
     * Simple anonymous function for generating absolute path
     * to translation file by given locale.
     */
    $buildPathCallback = function($locale, $ext = 'yml') {
            return sprintf(
                '%s/translations/%s.trans.%s',
                dirname(__DIR__),
                $locale,
                $ext
            );
    };

    /**
     * Check if translation file for default locale is present and load it.
     * --------------------------------------------------------------------
     *
     * For now, only one translation file is being loaded - the default one.
     * The reason for that is simple - while it's easy to use only one translation file
     * for all locales, and pass it to "translator.domains", this way we
     * keep unnecessary information in memory.
     *
     * There is an option called "locale_fallbacks" for "translator.domains",
     * which accepts an array of locales to be used if translation id cannot
     * be found in default messages. A future version of the project could include
     * some functionality to load and merge fallback translation files.
     */

     $app['translator'] = $app->share($app->extend('translator', function($translator, $app) use ($buildPathCallback, $locale) {
         $translator->addLoader('yaml', new YamlFileLoader());

         $translator->addResource('yaml', $buildPathCallback($locale), $locale);

         return $translator;
     }));
}
