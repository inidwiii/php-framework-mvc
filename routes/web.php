<?php

use App\Http\Controller\PageController;
use Src\Core\Request;
use Src\Core\Response;
use Src\Core\Route;

$route = new Route(new Request, new Response);

$route->get('/', [PageController::class, 'index'])->name('main')->middleware('first');
$route->get('/user', [PageController::class, 'index'])->name('main.user');
$route->get('/user/:id', [PageController::class, 'index'])->name('main.user.get');
$route->run();