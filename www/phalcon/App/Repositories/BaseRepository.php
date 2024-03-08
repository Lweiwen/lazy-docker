<?php

namespace Vokuro\App\Repositories;

use \Phalcon\Di;
use \Phalcon\DiInterface;
use Vokuro\App\Models\UserLoginBlackListModel;
use Vokuro\App\Models\WxOpenIdBlackListModel;

class BaseRepository
{

    /**
     * @var DiInterface 全局DI容器
     */
    protected $di;

    /**
     * @var Object DI容器的record服务
     */
    protected $record;

    /**
     * @var Object  DI容器的profiler服务
     */
    protected $profiler;

    /**
     * @var Vokuro\App\Core\PhalBaseLogger 记录在{日期}.log文件的日志服务
     * 可调用方法:   write_log(object|array|string|int $log, $level='error')
     */
    protected $logger;

    public function __construct(DiInterface $di = null)
    {
        $this->setDI($di);
        $this->setRecord();
        $this->setProfiler();
        !empty($this->di) && $this->logger = $this->di->get('logger');
    }

    /**
     * 设置DI容器
     * @param DiInterface|null $di
     */
    public function setDI(DiInterface $di = null)
    {
        empty($di) && $di = Di::getDefault();
        $this->di = $di;
    }

    /**
     * 获取DI容器
     * @return DiInterface
     */
    public function getDI()
    {
        return $this->di;
    }

    /**
     * 设置DI容器的record服务
     */
    public function setRecord()
    {
        !empty($this->di) && $this->record = $this->di->get('record');
    }

    /**
     * 设置DI容器的record服务
     */
    public function getRecord()
    {
        return $this->record;
    }

    /**
     * 设置DI容器的profiler服务
     */
    public function setProfiler()
    {
        !empty($this->di) && $this->profiler = $this->di->get('profiler');
    }

    /**
     * 设置DI容器的profiler服务
     */
    public function getProfiler()
    {
        return $this->profiler;
    }

    /**
     * 获取最后一句SQL语句
     */
    public function getLastSQL()
    {
        return $this->profiler->getLastProfile()->getSQLStatement();
    }

    /**
     * 获取所有SQL语句
     */
    public function getAllSQL()
    {
        return $this->profiler->getProfiles();
    }

    /**
     * exception日志记录
     * @param \Exception $e
     * @author Falcon
     */
    protected function write_exception_log(\Exception $e)
    {
        $logArray = array(
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'code'    => $e->getCode(),
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString(),
        );
        $this->di->get('logger')->write_log($logArray);
    }

    /**
     * exception日志记录
     * @param \Exception $e
     * @author Falcon
     */
    protected function write_money_exception_log(\Exception $e)
    {
        $logArray = array(
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'code'    => $e->getCode(),
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString(),
        );
        $this->di->get('money_record')->write_log($logArray);
    }

    protected function write_record_exception_log(\Exception $e)
    {
        $logArray = array(
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'code'    => $e->getCode(),
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString(),
        );
        $this->di->get('record')->write_log($logArray);
    }

}