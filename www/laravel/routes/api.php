<?php

use App\Http\Controllers\Api\V1 as api;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$api = app('Dingo\Api\Routing\Router');

/** @var \Dingo\Api\Routing\Router $api */
$api->version('v1',
    [
        'middleware' => ['bindings'],
    ], function ($api) {
        $api->get('test', [api\TestController::class, 'test']);

    });
