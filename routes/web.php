<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use App\Http\Controllers\HomeController;

$router = app('router');

$router->any('/{all?}', HomeController::class . '@index')
    ->where('all', '^(?!api).*$')
    ->where('all', '^(?!log-viewer).*$')
    ->where('all', '^(?!broadcasting).*$');

