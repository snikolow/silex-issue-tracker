<?php

define('ROOT_PATH', dirname(__DIR__));

require ROOT_PATH . '/vendor/autoload.php';

ini_set('error_log', ROOT_PATH . '/var/log/');

$app = new Tracker\Application();
$app->configureOptions();
