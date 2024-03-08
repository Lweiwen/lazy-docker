<?php

namespace app\controller;

use app\service\TestService;
use support\Request;

class TestController extends BaseController
{
    public function index()
    {
        $result = (new TestService())->test();
        return $this->json($result);
    }
}