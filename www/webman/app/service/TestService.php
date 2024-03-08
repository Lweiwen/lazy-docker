<?php

namespace app\service;
class TestService
{
    public function test(): array
    {
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