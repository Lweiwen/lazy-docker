<?php

/**
 * Phalcon CLI扩展
 */

namespace Vokuro\App\Core;

class PhalBaseTask extends \Phalcon\CLI\Task{

    public function initialize()
    {

    }

    /**
     * exception日志记录，记录在{日期}.log
     * @param \Exception $e
     * @author Falcon
     */
    protected function write_exception_log(\Exception $e)
    {
        $logArray = array(
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        );
        $this->logger->write_log($logArray);
    }

    /**
     * 记录exception到record记录，记录record_{日期}.log
     * @param \Exception $e
     * @author RaysonLu
     */
    protected function write_record_exception_log(\Exception $e){
        $logArray = array(
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        );
        $this->record->write_log($logArray);
    }

    protected function write_money_exception_log(\Exception $e)
    {
        $logArray = array(
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        );
        $this->di->get('money_record')->write_log($logArray);
    }
}