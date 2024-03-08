<?php

/**
 * Phalcon日志扩展
 */

namespace Vokuro\App\Core;

class PhalBaseLogger extends \Phalcon\Logger\Adapter\File{

    /**
     * 日志记录
     * @param $log
     * @param $level 日志等级   分别有CRITICAL,EMERGENCY,DEBUG,ERROR,INFO,NOTICE,WARNING,ALERT,ERROR
     * @link https://docs.phalconphp.com/zh/latest/reference/logging.html
     */
    public function write_log($log, $level='error'){
        if(is_array($log) ){
            $log = json_encode($log);
        }elseif(is_object($log)){
            $log = serialize($log);
        }
        empty($level) && $level = 'error';
        $level = strtolower($level);
        $this -> $level($log);
    }
}