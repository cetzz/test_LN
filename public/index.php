<?php

require '../vendor/autoload.php';
require '../src/conf.php';
require '../src/functions.php';
require '../src/database.php';
require '../main/init.php';
require '../main/getResources.php';
require '../main/modifyResources.php';

$config = [
    'settings' => [
        'displayErrorDetails' => true
    ],
];
$app = new \Slim\App($config);
require '../src/routes.php';
$app->run();


?>