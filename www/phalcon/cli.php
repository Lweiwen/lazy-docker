<?php

try {

    if(strpos(php_sapi_name(), 'cli') === false){
        throw new \Exception('403 Forbidden');
    }

    define('ROOT_PATH', __DIR__);
    define('APP_PATH', ROOT_PATH . '/App');

    define("BASE_URL","https://api.ljbao.net");
    //前端URL
    define("FRONT_END_URL",'https://t.ljbao.net');
    define("FRONT_END_ERROR_URL",'https://t.ljbao.net/error/notFound');
    define("FRONT_END_LOGIN_ERROR_URL",'https://t.ljbao.net/error/login');
    //web前端URL
    define("WEB_END_URL",'https://www.ljbao.net');
    //img地址
    define("IMG_URL",'https://img.ljbao.net');
    //招商地址
    define('ZS_END_URL', 'https://zs.ljbao.net');

    set_time_limit(0);

    /**
     * 引入loader.php
     */
    include APP_PATH . '/Core/loader.php';

    /**
     * 引入Composer autoload
     */
    if(file_exists(ROOT_PATH . '/vendor/autoload.php')) {
        require ROOT_PATH . '/vendor/autoload.php';
    }

    /**
     * 引入系统常量
     */
    include APP_PATH . '/Core/constant.php';

    /**
     * 加载.env环境配置
     */
    $dotenv = new Dotenv\Dotenv(ROOT_PATH);
    $dotenv -> load();

    /**
     * 加载系统配置
     */
    $config = new \Phalcon\Config\Adapter\Php(APP_PATH . "/Config/application.php");

    /**
     * 引入services.php
     */
    include APP_PATH . '/Core/services_cli.php';

    /**
     * 处理请求
     */
    $console = new \Phalcon\CLI\Console();
    $console -> setDI($di);

    $url = $argv[1];
    $urlArray = parse_url($url);
    if(!is_array($urlArray) || count($urlArray) == 0){
        throw new \Exception('url parse error');
    }
    if(!isset($urlArray['path']) || empty($urlArray['path'])){
        throw new \Exception('url parse error');
    }
    $path = trim($urlArray['path']);
    $query = isset($urlArray['query']) ? trim($urlArray['query']) : '';
    $path = trim($path, '/');
    $pathArray = explode('/', $path);
    if(!isset($pathArray[0]) || empty($pathArray[0])){
        throw new \Exception('task name is empty');
    }
    if(!isset($pathArray[1]) || empty($pathArray[1])){
        throw new \Exception('action name is empty');
    }
    $taskName = '\Vokuro\App\Tasks\\' . ucwords($pathArray[0]);
    $actionName = $pathArray[1];
    if(!empty($query)){
        $query = explode('-',$query);
    }

    $arguments = array(
        'task' => $taskName,
        'action' => $actionName,
        'params' => $query
    );
    $console -> handle($arguments);

}catch (\Exception $e) {
    echo $e -> getMessage().'  DETAILS:'.$e->getFile().'__'.$e->getLine();
}