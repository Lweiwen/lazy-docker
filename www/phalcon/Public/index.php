<?php
/**
 * User: Marser
 * Date: 2017/4/11
 * Time: 10:32
 */

try {
    date_default_timezone_set('PRC');

    define('ROOT_PATH', dirname(__DIR__));
    define('APP_PATH', ROOT_PATH . '/App');

    /**
     * 引入loader.php
     */
    include APP_PATH . '/Core/loader.php';

    /**
     * 引入Composer autoload
     */
    if (file_exists(ROOT_PATH . '/vendor/autoload.php')) {
        require ROOT_PATH . '/vendor/autoload.php';
    }

    /**
     * 引入系统常量
     */
//    include APP_PATH . '/Core/constant.php';

    /**
     * 加载.env环境配置
     */
//    $dotenv = new Dotenv\Dotenv(ROOT_PATH);
//    $dotenv->load();


    /**
     * 加载系统配置
     */
//    $config = new \Phalcon\Config\Adapter\Php(APP_PATH . "/Config/application.php");

    /**
     * 引入services.php
     */
    include APP_PATH . '/Core/services.php';

    /**
     * 处理请求
     */
    $application = new \Phalcon\Mvc\Micro($di);

    /**
     * 引入routes.php
     */
    include APP_PATH . '/Core/router.php';

    $application->handle();

} catch (\Exception $e) {
    $log = array(
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'code' => $e->getCode(),
        'msg' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    );

    $date = date('Ymd');
    $logger = new \Phalcon\Logger\Adapter\File(APP_PATH . "/Cache/Logs/crash_{$date}.log");
    $logger->error(json_encode($log));

    $response = new Phalcon\Http\Response();
    if (getenv('runtime') != 'dev') {
        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Headers', 'Authorization, Origin, X-Requested-With, Content-Type, Accept, mediaId, openid');
    }
    $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
    $response->setJsonContent([
        'code' => $e->getCode(),
        'msg' => $e->getMessage(),
        'data' => [],
    ]);
    $response->send();
}