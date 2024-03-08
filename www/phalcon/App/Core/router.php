<?php

/**
 * User: Marser
 * Date: 2017/4/11
 * Time: 13:33
 */


$routes = $di->get('routerConfig')->toArray();
if(!is_array($routes) || count($routes)==0){
    throw new \Exception('系统异常');
}

foreach($routes as $version=>$route){
    foreach ($route as $prefix => $collection) {
        $handler = new \Phalcon\Mvc\Micro\Collection();
        $handler->setHandler($collection['handler'], true);
        $handler->setPrefix("/{$version}/{$prefix}");
        foreach ($collection['rules'] as $rule => $value) {
            $method = array_keys($value);
            $method = end($method);
            $method = strtolower($method);
            $action = array_values($value);
            $action = end($action);
            $handler->options($rule, 'cors');
            $handler->$method($rule, $action);
        }
        $application->mount($handler);
    }
}

//设置Notfound
$application->notFound(function () use ($application) {
    $application->response->setStatusCode(404, 'not found');
    $application->response->send();
});
