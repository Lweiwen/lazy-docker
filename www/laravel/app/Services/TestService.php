<?php
namespace App\Services;
class TestService
{
    /**
     * 返回测试数据
     * @return array
     * @author LWW
     */
    public function test()
    {
        // 业务逻辑
        return [
            'name'    => 'test',
            'age'     => 20,
            'sex'     => 'man',
            'address' => 'china',
            'email'   => 'test@qq.com',
            'phone'   => '13812345678',
            'qq'      => '123456789',
            'wechat'  => '123456789',
            'weibo'   => '123456789',
        ];
    }
}