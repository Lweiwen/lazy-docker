<?php

declare(strict_types=1);

namespace App\Controller\Api\V1\Web;

use App\Controller\BaseController;
use App\Services\AuthLogin\UserAuthenticate;
use App\Services\Web\PromotersService;

class PromotersController extends BaseController
{
    protected PromotersService $service;

    //
    public function becomePromoter()
    {
    }

    public function sharePromoter(): \Swow\Psr7\Message\ResponsePlusInterface
    {
        $userId = UserAuthenticate::id();
        return $this->response->success($this->service->sharePromoterUrl($userId));
    }


}