<?php

/**
 * API路由配置
 */

return array(
    'v1' => [
        'test' => [
            'handler' => '\Vokuro\App\Controllers\V1\TestController',
            'rules' => [
                '/index' => ['get' => 'index'],
            ]
        ],
    ]
);