<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Dingo\Api\Routing\Helpers;

class Controller extends BaseController
{
    use DispatchesJobs, Helpers;

    public function validatorCheck(array $data, array $rules, array $message = [], array $attributes = [])
    {
        //没有数据或者没有规则，不需要做验证操作
        if (empty($rules)) {
            return false;
        }
        $validator = Validator::make($data, $rules, $message, $attributes);
        //验证失败
        if ($validator->fails()) {
            throw new ApiException($validator->errors()->first(), 0);
        }
        return true;
    }

    /**
     * 分页参数获取
     * $limit 每页数目,默认20,最大50
     * $offset($page) 页数，前端传来的offset表示页数，接收参数后换算成数据查询偏移量的意思
     * @return array 返回按顺序包含 搜索数目、偏移量 的数组
     * @author lww
     */
    public function pagination()
    {
        $limit = intval(request()->query('limit', 20));
        $page = intval(request()->query('offset', 1));
        $limit = $limit > 0 ? $limit <= 50 ? $limit : 20 : 20;
        $offset = ($page - 1) * $limit;

        return array($limit, $offset);
    }

    public function apiResponse(array $data,string $message = 'success', $code = 100)
    {
        if (!is_integer($code)) {
            $code = (is_numeric($code)) ? intval($code) : 0;
        }
        $CT = '0';
        if (!is_null(auth()->user())) {
            $tlt = auth()->tlt();//token 剩余时间
            if (!is_null($tlt) && $tlt <= 3600) {
                //判断token剩余时间少于1小时，则在header返回参数通知前端可以重新获取token
                $CT = '1';
            }
        }
        $arrHeaders = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Authorization, Origin, X-Requested-With, Content-Type, Accept',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE',
        ];
        if($CT == '1'){
            $arrHeaders['Cache-Control'] = 'no-store';
            $arrHeaders['Pragma'] = 'no-cache';
            $arrHeaders['Access-Control-Expose-Headers'] = 'C-T';
            $arrHeaders['C-T'] = '1';
        }
        return $this->response->array([
            'code' => $code,
            'msg' => $message,
            'data' => $data
        ])->withHeaders($arrHeaders);
    }

    public function wxpayApiResponse(string $code,string $message)
    {
        return $this->response->array([
            'code' => $code,
            'message' => $message,
        ]);
    }
}

