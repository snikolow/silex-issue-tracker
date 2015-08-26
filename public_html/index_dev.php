<?php

require_once 'bootstrap.php';

$app['debug'] = true;
$app->initControllerResolver();
$app->run();