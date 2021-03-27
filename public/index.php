<?php

require realpath(dirname(__DIR__) . '/app/bootstrap.php');

$app = new \Gov\Core\Application(
    $_GET['url'] ?? '/',
    PATH_APP . 'config' . DS,
    PATH_APP . 'view' . DS
);

$app->boot();
