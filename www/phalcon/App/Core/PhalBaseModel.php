<?php

/**
 * Phalcon模型扩展
 */

namespace Vokuro\App\Core;

class PhalBaseModel extends \Phalcon\Mvc\Model implements \Phalcon\Mvc\ModelInterface
{

    /**
     * 数据库连接对象
     * @var \Phalcon\Db\Adapter\Pdo\Mysql
     */
    protected $_db;

    public function initialize()
    {
        $this->_db = $this->getDI()->get('db');
        /** 不对空字段进行validation校验 */
        self::setup(array(
            'notNullValidations' => false
        ));
    }

    /**
     * 设置表（补上表前缀）
     * @param string $tableName
     * @param null $prefix
     */
    protected function set_table_source($tableName, $prefix = null)
    {
        empty($prefix) && $prefix = $this->getDI()->get('config')->database->prefix;
        $this->setSource($prefix . $tableName);
    }

    /**
     * 批量添加记录
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function batch_insert(array $data)
    {
        if (count($data) == 0) {
            throw new \Exception('参数错误');
        }
        $keys = array_keys(reset($data));
        $keys = array_map(function ($key) {
            return "`{$key}`";
        }, $keys);
        $keys = implode(',', $keys);
        $sql = "INSERT INTO " . $this->getSource() . " ({$keys}) VALUES ";
        foreach ($data as $v) {
            $v = array_map(function ($value) {
                return "'{$value}'";
            }, $v);
            $values = implode(',', array_values($v));
            $sql .= " ({$values}), ";
        }
        $sql = rtrim(trim($sql), ',');
        $result = $this->_db->execute($sql);
        if (!$result) {
            throw new \Exception('批量入库记录');
        }
        return $result;
    }
}