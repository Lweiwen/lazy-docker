<?php

use App\Controller\Api\V1 as V1;
use App\Controller\Api\V1\Backstage as adminV1;
use Hyperf\HttpServer\Router\Router;

Router::addGroup('/v1/backstage', function () {
    Router::post('/login', [adminV1\LoginController::class, 'login']);
    Router::get('/test/{id}', [adminV1\LoginController::class, 'test'], [
        'middleware' => [App\Middleware\PcAuthMiddleware::class],
    ]);
    Router::get('/test2/{id}', [V1\TestController::class, 'updateUser'], [
        'middleware' => [App\Middleware\PcAuthMiddleware::class],
    ]);

});
Router::addGroup('/v1/common', function () {
    Router::post('/images/upload', [V1\AttachmentController::class, 'upload'], [
        'middleware' => [App\Middleware\PcAuthMiddleware::class],
    ]);
});