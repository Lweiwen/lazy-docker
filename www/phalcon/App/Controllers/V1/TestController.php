<?php

namespace Vokuro\App\Controllers\V1;

use Phalcon\Mvc\Controller;
use Vokuro\App\Repositories\Test;


class TestController extends Controller
{
    /**
     * 返回数据
     * @author LWW
     */
    public function index()
    {
        $result = (new Test())->test();
        // 返回JSON数据
        return $this->ajax_return(1, 'success', $result);
    }

    protected function ajax_return($code = 1, string $message = 'success', array $data = array(), string $callback = '', array $otherResult = array())
    {
        $result = array(
            'code' => $code,
            'msg' => $message,
            'data' => $data,
        );

        empty($callback) && $callback = $this->request->get('callback', 'trim');
        if (empty($callback)) {
            if (getenv('runtime') != 'dev') {
                $this->response->setHeader('Access-Control-Allow-Origin', '*');
                $this->response->setHeader('Access-Control-Allow-Headers', 'Authorization, Origin, X-Requested-With, Content-Type, Accept, mediaId, openid');
            }
            $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');

            $this->response->setJsonContent($result);
        } else {
            if (!empty($otherResult))
                $result = array_merge($result, $otherResult);
            unset($result['data']);
            $content = "{$callback}(" . json_encode($result) . ")";
            $this->response->setContent($content);
        }

        $this->response->send();
    }
}


