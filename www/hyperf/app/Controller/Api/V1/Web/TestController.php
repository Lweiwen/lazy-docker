<?php

declare(strict_types=1);

namespace App\Controller\Api\V1\Web;

use App\Controller\BaseController;
use App\Services\TestService;
use Hyperf\Di\Annotation\Inject;

class TestController extends BaseController
{
    #[Inject]
    protected TestService $service;
    /**
     * 测试方法
     * @return \Swow\Psr7\Message\ResponsePlusInterface
     * @author LWW
     */
    public function test(): \Swow\Psr7\Message\ResponsePlusInterface
    {
        return $this->response->success($this->service->test());
    }
}