<?php

$app = require realpath(dirname(__DIR__) . '/src/autoload.php');

$req = new \Illuminate\Core\Request();
$res = new \Illuminate\Core\Response();

if ($req->path() == '/gov/hello') $res->redirect($req->baseUrl('hello/world'));