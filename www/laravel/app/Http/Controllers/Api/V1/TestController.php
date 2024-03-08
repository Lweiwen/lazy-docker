<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Services\TestService;

class TestController extends Controller
{
    public function test()
    {
        $result = (new TestService())->test();
        return $this->apiResponse($result);
    }
}
