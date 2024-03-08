<?php
declare(strict_types=1);
namespace App\Controller\Api\V1\Web;
use App\Controller\BaseController;

class MerchantController extends BaseController
{
    /**
     * 申请加入商家
     * @return \Swow\Psr7\Message\ResponsePlusInterface
     * @author LWW
     */
    public function apply(): \Swow\Psr7\Message\ResponsePlusInterface
    {
        return $this->response->success();
    }
}