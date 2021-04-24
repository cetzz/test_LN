<?php

require '../vendor/autoload.php';
require '../src/conf.php';
require '../src/database.php';
require '../src/functions.php';
require '../main/init.php';
require '../main/getResources.php';

$config = [
    'settings' => [
        'displayErrorDetails' => true
    ],
];
$app = new \Slim\App($config);
require '../src/routes.php';
$app->run();


?>