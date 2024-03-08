<?php

/**
 * DI注册服务配置文件
 */

use Phalcon\DI\FactoryDefault\CLI;

$di = new CLI();

/**
 * DI注册分析器
 */
$di->set('profiler',function(){
    return new \Phalcon\Db\Profiler();
},true);

/**
 * DI注册DB配置
 */
$di->setShared('db', function () use ($config, $di) {
    $dbconfig = $config->database;
    if (getenv('runtime') != 'pro') {
        $eventsManager = new \Phalcon\Events\Manager();
        // 分析底层sql性能，并记录日志
        $profiler = $di->getProfiler();
        $eventsManager->attach('db', function ($event, $connection) use ($profiler, $di) {
            if ($event->getType() == 'beforeQuery') {
                //在sql发送到数据库前启动分析
                $profiler->startProfile($connection->getSQLStatement());
            }
            if ($event->getType() == 'afterQuery') {
                //在sql执行完毕后停止分析
                $profiler->stopProfile();
                //获取分析结果
                $profile = $profiler->getLastProfile();
                $sql = $profile->getSQLStatement();
                $params = $connection->getSqlVariables();
                (is_array($params) && count($params)) && $params = json_encode($params);
                $executeTime = $profile->getTotalElapsedSeconds();
                //日志记录
                $logger = $di->get('logger');
                $logger->write_log("{$sql} {$params} {$executeTime}", 'debug');
            }
        });
    }

    $connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
            "host" => $dbconfig['host'],
            "port" => $dbconfig['port'],
            "username" => $dbconfig['username'],
            "password" => $dbconfig['password'],
            "dbname" => $dbconfig['dbname'],
            "charset" => $dbconfig['charset'],
            "options" => [
                PDO::ATTR_EMULATE_PREPARES => FALSE,  //禁止本地模拟prepare
            ])
    );

    if (getenv('runtime') != 'pro') {
        /* 注册监听事件 */
        $connection->setEventsManager($eventsManager);
        /* 注册监听事件 */
    }

    return $connection;
});

/**
 * 主DB配置
 */
$di->setShared('db_main', function () use ($config, $di) {
    if(getenv('runtime')!='pro'){
        $dbconfig = $config->database;      //非正式环境
    }else{
        $dbconfig = $config->database_main;
    }

    $connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
            "host" => $dbconfig['host'],
            "port" => $dbconfig['port'],
            "username" => $dbconfig['username'],
            "password" => $dbconfig['password'],
            "dbname" => $dbconfig['dbname'],
            "charset" => $dbconfig['charset'],
            "options" => [
                PDO::ATTR_EMULATE_PREPARES => FALSE,  //禁止本地模拟prepare
            ])
    );
    return $connection;
});

/**
 * 备用数据库连接
 */
$di->setShared('db2', function () use ($config, $di) {
    $dbconfig = $config->database;
    $connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
            "host" => $dbconfig['host'],
            "port" => $dbconfig['port'],
            "username" => $dbconfig['username'],
            "password" => $dbconfig['password'],
            "dbname" => $dbconfig['dbname'],
            "charset" => $dbconfig['charset'],
            "options" => [
                PDO::ATTR_EMULATE_PREPARES => FALSE,  //禁止本地模拟prepare
            ])
    );
    return $connection;
});

/**
 * DI注册modelsManager服务
 */
$di->setShared('modelsManager', function () use ($di) {
    return new Phalcon\Mvc\Model\Manager();
});

/**
 * DI注册缓存服务
 */
$di->setShared('cache', function () use ($config) {
    return new \Phalcon\Cache\Backend\File(new \Phalcon\Cache\Frontend\Output(), array(
        'cacheDir' => $config->app->cache_path
    ));
});

/**
 * DI注册日志服务
 */
$di->setShared('logger', function () use ($config) {
    $day = date('Ymd');
    $logger = new \Vokuro\App\Core\PhalBaseLogger(APP_PATH . "/Cache/Logs/{$day}.log");
    return $logger;
});

/**
 * DI注册自定义记录日志服务
 */
$di->setShared('record', function () use ($config) {
    $day = date('Ymd');
    $logger = new \Vokuro\App\Core\PhalBaseLogger(APP_PATH . "/Cache/Logs/record_{$day}.log");
    return $logger;
});

/**
 * DI注册金钱错误记录日志服务
 */
$di->setShared('money_record', function () use ($config) {
    $day = date('Ymd');
    $logger = new \Vokuro\App\Core\PhalBaseLogger(APP_PATH . "/Cache/Logs/money_record_{$day}.log");
    return $logger;
});

/**
 * DI注册system配置
 */
$di->setShared('config', function () use ($config) {
    $apiConfig = new \Phalcon\Config\Adapter\Php(APP_PATH . '/Config/api.php');
    $config->merge($apiConfig);

    return $config;
});

/**
 * DI注册id转换器
 */
$di->setShared('idConvert',function(){
    $idConvert = new \Vokuro\App\Libs\IdConvert();
    return $idConvert;
});

/**
 * Redis
 */
$di->setShared('redis',function() use($config){
    $rdConfig = $config->redis;
    return new \Phalcon\Cache\Backend\Redis(
        new \Phalcon\Cache\Frontend\Data(['lifetime'=>0]),
        [
            'prefix' => 'LJB',
            'lifetime' => 86400,
            'host' => $rdConfig['host'],
            'port' => $rdConfig['port'],
            'persistent' => false
        ]
    );
});

$di->setShared('phpRedis',function() use($config){
    try{
        $rdConfig = $config->redis;
        $redis = new Redis();
        $redis->open($rdConfig['host'],$rdConfig['port']);
        if(!empty($rdConfig['auth'])){
            $authResult = $redis->auth($rdConfig['auth']);
            if(!$authResult)
                return false;
        }
        $redis->select(0);
    }catch(\Exception $e){
        return false;
    }
    return $redis;
});


$di->setShared('aliRedis',function() use($config){
    try{
        if(getenv('runtime')=='pro'){
            $rdConfig = $config->ali_redis;
        }else{
            $rdConfig = $config->redis;
        }

        @$redis = new Redis();
        @$redis->open($rdConfig['host'],$rdConfig['port'],5);
        if(!empty($rdConfig['auth'])){
            $authResult = $redis->auth($rdConfig['auth']);
            if(!$authResult)
                return false;
        }
        $redis->select(0);
    }catch(\Exception $e){
        return false;
    }
    return $redis;
});

/**
 * DI注册省市区数据
 */
$di->setShared('area', function () use ($di){
    $area = new Phalcon\Config\Adapter\Json(APP_PATH . '/Cache/Data/ChinaArea.json');
    return $area;
});

/**
 * DI注册自定义验证器
 */
$di->setShared('validator', function () use ($di) {
    $validator = new \Vokuro\App\Libs\Validator($di);
    return $validator;
});