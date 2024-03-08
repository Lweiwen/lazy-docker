<?php

/**
 * 注册命名空间
 */

$loader = new \Phalcon\Loader();

/**
 * 注册命名空间
 */
$loader -> registerNamespaces(array(
    'Vokuro' => ROOT_PATH,
));
//
//$loader->registerClasses(array(
//    'PHPExcel' => APP_PATH . '/Libs/Excel/PHPExcel.php',	// 注册PHPExcel类库
//));

$loader-> register();