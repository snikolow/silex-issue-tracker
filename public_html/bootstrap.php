<?php

define('ROOT_PATH', dirname(__DIR__));

require ROOT_PATH . '/vendor/autoload.php';

ini_set('error_log', ROOT_PATH . '/var/log/');

use App\Component\Application;

$app = new Application();

require_once ROOT_PATH . '/src/Resources/config/config.php';
require_once ROOT_PATH . '/src/Resources/config/widgets.php';
require_once ROOT_PATH . '/src/Resources/config/providers.php';
require_once ROOT_PATH . '/src/Resources/config/forms.php';
require_once ROOT_PATH . '/src/Resources/config/routes.php';
require_once ROOT_PATH . '/src/Resources/config/twig.php';
require_once ROOT_PATH . '/src/Resources/config/translations.php';
require_once ROOT_PATH . '/src/Resources/config/services.php';