<?php

define('ROOT_PATH', dirname(__DIR__));

require ROOT_PATH . '/vendor/autoload.php';

ini_set('error_log', ROOT_PATH . '/var/log/');

use App\Component\Application;

$app = new Application();

$configs = array(
        'config.php',
        'widgets.php',
        'providers.php',
        'forms.php',
        'routes.php',
        'twig.php',
        'translations.php',
        'services.php'
);

foreach($configs as $file) {
    $filepath = sprintf('%s/src/Resources/config/%s', ROOT_PATH, $file);
    
    require $filepath;
}