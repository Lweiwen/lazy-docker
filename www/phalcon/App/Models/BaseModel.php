<?php
/**
 * User: Marser
 * Date: 2017/4/25
 * Time: 10:57
 */

namespace Vokuro\App\Models;

class BaseModel extends \Vokuro\App\Core\PhalBaseModel {

    public function initialize(){
        parent::initialize();

        $this->useDynamicUpdate(true);      // 动态更新
        $this->keepSnapshots(true);          // 开启记录快照
        $this->keepSnapshots(true);          // 开启记录快照
    }

    //使用主库进行读写操作
    public function useMainDB(){
        $this->setConnectionService('db_main');
    }

    //使用主库进行读操作(读写分离代理机会将写操作、事务操作分配到主库机)
    public function readByMainDB(){
        $this->setReadConnectionService('db_main');
    }

    //使用数据库实例2进行读写操作(在事务中使用其他数据库实例连接数据库进行读写操作，将不会包括在该事务内)
    public function useDB2(){
        $this->setConnectionService('db2');
    }

}