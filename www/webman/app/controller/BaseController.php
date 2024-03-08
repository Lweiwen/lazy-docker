<?php

namespace app\controller;

class BaseController
{
    public function json(array $data = [], string $msg = 'ok', int $code = 0): \support\Response
    {
        return json(['code' => $code, 'msg' => $msg, 'data' => $data]);
    }

}