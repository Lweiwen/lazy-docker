<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ApiException;
use App\Kernel\LJConvert\LJConvert;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

class BaseController extends Controller
{
    #[Inject]
    protected ValidatorFactoryInterface $validationFactory;

    /**
     * 分页参数获取
     * $limit 每页数目,默认20,最大50
     * $offset($page) 页数，前端传来的offset表示页数，接收参数后换算成数据查询偏移量的意思
     * @return array 返回按顺序包含 搜索数目、偏移量 的数组
     * @author lww
     */
    public function pagination(): array
    {
        $limit = intval($this->request->query('limit', 20));
        $page = intval($this->request->query('offset', 1));
        $limit = $limit > 0 ? ($limit <= 50 ? $limit : 20) : 20;
        $offset = ($page - 1) * $limit;

        return array($limit, $offset);
    }

    /**
     * 验证器
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $attributes
     * @return bool
     * @author LWW
     */
    public function validatorCheck(
        array $data = [],
        array $rules = [],
        array $messages = [],
        array $attributes = []
    ): bool {
        $validator = $this->validationFactory->make($data, $rules, $messages, $attributes);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            throw new ApiException($error, 0);
        }
        return true;
    }

}