<?php

/**
 * 系统配置
 */

return array(
    'app' => array(

        //根命名空间
        'root_namespace' => '\\Vokuro\\',

        //模块在URL中的pathinfo路径名
        'module_pathinfo' => '/',

        //控制器路径
        'controllers' => APP_PATH . '/Controllers/',

        //日志根目录
        'log_path' => APP_PATH . '/Cache/Logs/',

        //缓存路径
        'cache_path' => APP_PATH . '/Cache/Data/',
    ),

    'database' => [
        'host' => getenv('database_host'),
        'port' => getenv('database_port'),
        'username' => getenv('database_username'),
        'password' => getenv('database_password'),
        'dbname' => getenv('database_dbname'),
        'charset' => getenv('database_charset'),
        'prefix' => getenv('database_prefix'), //数据库表前缀配置
    ],
);