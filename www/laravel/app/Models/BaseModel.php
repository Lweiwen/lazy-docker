<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BaseModel extends Model
{

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];

    /**
     * 批量更新
     * $data = [
     *      'id' => 1, 'name' => 1, 'xxx' => 2,
     *      'id' => 2, 'name' => 2, 'xxx' => 7,
     * ]
     * @param array $data
     * @return bool|int
     * @author LYF
     */
    protected function batchUpdate(array $data)
    {
        if (empty($data)) {
            return false;
        }
        $firstRow = current($data);
        $updateColumn = array_keys($firstRow);
        //默认以id为条件更新，如果没有id则以第一字段为条件
        $referenceColumn = isset($firstRow['id']) ? 'id' : current($updateColumn);
        unset($updateColumn[0]);

        $sql = 'UPDATE '.DB::connection()->getTablePrefix(). $this->getTable(). ' SET ';
        $sets      = [];
        $bindings  = [];
        foreach ($updateColumn as $uColumn) {
            $setSql = "`" . $uColumn . "` = CASE ";
            foreach ($data as $item) {
                $setSql .= "WHEN `" . $referenceColumn . "` = ? THEN ? ";
                $bindings[] = $item[$referenceColumn];
                $bindings[] = $item[$uColumn];
            }
            $setSql .= " END ";
            $sets[] = $setSql;
        }
        $sql .= implode(', ', $sets);
        $whereIn   = collect($data)->pluck($referenceColumn)->values()->all();
        $bindings  = array_merge($bindings, $whereIn);
        $whereIn   = rtrim(str_repeat('?,', count($whereIn)), ',');
        $sql = rtrim($sql, ", ") . " WHERE `" . $referenceColumn . "` IN (" . $whereIn . ")";
        // 传入预处理sql语句和对应绑定数据
        return DB::update($sql, $bindings);
    }
}
