<?php

declare(strict_types=1);

namespace App\Controller\Api\V1\Web;

use App\Controller\BaseController;
use App\Services\AuthLogin\Login\PasswordLogin;
use Hyperf\Di\Annotation\Inject;

class LoginController extends BaseController
{
    /**
     * @var PasswordLogin
     */
    #[Inject]
    private PasswordLogin $service;

    /**
     * 账号密码登录
     * @return \Swow\Psr7\Message\ResponsePlusInterface
     * @throws \Exception
     * @author LWW
     */
    public function accountLogin(): \Swow\Psr7\Message\ResponsePlusInterface
    {
        $account = $this->request->post('account', '');
        $password = $this->request->post('password', '');
        $this->validatorCheck(
            ['account' => $account, 'password' => $password],
            [
                'account'  => 'required',
                'password' => 'required',
            ],
            [
                'account.required'  => '账号必填',
                'password.required' => '密码必填',
            ]
        );
        return $this->response->success($this->service->getWebLoginToken($account, $password));
    }

    /**
     * 创建活动
     * @author LWW
     */
    public function addActivity()
    {
        return $this->response->success('成功');
    }
}