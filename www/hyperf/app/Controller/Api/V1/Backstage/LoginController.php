<?php

declare(strict_types=1);

namespace App\Controller\Api\V1\Backstage;

use App\Controller\BaseController;
use App\Services\AuthLogin\Login\PasswordLogin;
use App\Services\AuthLogin\UserAuthenticate;
use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Swow\Psr7\Message\ResponsePlusInterface;

class LoginController extends BaseController
{

    /**
     * @var PasswordLogin
     */
    #[Inject]
    private PasswordLogin $service;

    /**
     * 登录接口
     * @return ResponsePlusInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @author LWW
     */
    public function login(): ResponsePlusInterface
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
        return $this->response->success($this->service->getBackstageLoginToken($account, $password));
    }

    public function refresh()
    {
        $refreshJwt = $this->request->post('refresh_jwt');
        $relationType = $this->request->post('relation_type', '');
        $relationId = $this->request->post('relation_id', '');
        $this->validatorCheck(
            ['refresh_jwt' => $refreshJwt],
            [
                'refresh_jwt' => 'required',
                'string'
            ],
            [
                'refresh_jwt.required' => 'refresh_jwt 必填',
                'refresh_jwt.string'   => 'refresh_jwt 只能是字符串',
            ]
        );
        $this->response->success($this->service->refreshJwt($refreshJwt));
    }

    /**
     * 测试
     * @return ResponsePlusInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @author LWW
     */
    public function test(): ResponsePlusInterface
    {
        $code = code_to_id_lj(id_to_code_lj(1));
        return $this->response->success(['user_id' => UserAuthenticate::id()]);
    }

}