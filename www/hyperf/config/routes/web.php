<?php

use App\Controller\Api\V1\Web as webV1;
use Hyperf\HttpServer\Router\Router;

Router::addGroup('/v1/web', function () {
    Router::post('/login', [webV1\LoginController::class, 'accountLogin']);

    Router::post('/test/{id}', [webV1\LoginController::class, 'addActivity'], [
        'middleware'    => [App\Middleware\AuthMiddleware::class],
        'check_type'    => 'system',
        'user_identify' => [CLIENT],
        'handler'       => 'businessSupper'
    ]);

    Router::post('/test2/{activity_code}', [webV1\LoginController::class, 'addActivity'], [
        'middleware'    => [App\Middleware\AuthMiddleware::class],
        'check_type'    => 'activity',
        'handler'       => 'activityProviderSupper'
    ]);
    //申请商家
    Router::post('/merchant/apply', [webV1\MerchantController::class, 'apply'], [
        'middleware'    => [App\Middleware\AuthMiddleware::class],
    ]);

    Router::get('/test', [webV1\TestController::class, 'test']);

});