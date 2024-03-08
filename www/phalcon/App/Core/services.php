<?php

/** @var \IdeHelper\configHelper $config */
global $config;
/**
 * DI注册服务配置文件
 */

$di = new \Phalcon\DI\FactoryDefault();
/**
 * DI注册system配置
 */
//$di->setShared('config', function () use ($config) {
//    $apiConfig = new \Phalcon\Config\Adapter\Php(APP_PATH . '/Config/api.php');
//    $config->merge($apiConfig);
//
//    return $config;
//});

/**
 * DI注册router配置
 */
$di->setShared('routerConfig', function () use ($di) {
    $config = new \Phalcon\Config\Adapter\Php(APP_PATH . '/Config/routes.php');
    return $config;
});