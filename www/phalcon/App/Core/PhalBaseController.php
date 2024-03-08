<?php

/**
 * Phalcon控制器扩展
 *
 */

namespace Vokuro\App\Core;

use \Phalcon\Mvc\Controller;

/**
 * @property \Vokuro\App\Libs\Validator $validator
 * @property \Vokuro\App\Core\PhalBaseLogger $logger
 * @property \Vokuro\App\Libs\IdConvert $idConvert
 * @property \Redis|boolean $phpRedis
 * 
 */
class PhalBaseController extends Controller
{

    public function initialize()
    {

    }

    /**
     * 页面跳转
     * @param null $url
     */
    protected function redirect($url = NULL)
    {
        empty($url) && $url = $this->request->getHeader('HTTP_REFERER');
        $this->response->redirect($url);
        $this->response->send();
    }

    /**
     * ajax输出
     * @param int $code
     * @param string $message
     * @param array $data
     * @param string $callback
     * @param array $otherResult
     * @author RaysonLu
     */
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

    /**
     * json输出,ajax_return的自定义结构方法
     * @param array $result
     * @param string $contentType
     * @param string $callback
     */
    protected function json_return($result = array(), string $contentType = '', string $callback = '')
    {
        empty($callback) && $callback = $this->request->get('callback', 'trim');
        if (!is_array($result)) {
            if (is_string($result)) {
                if (trim($result)) {
                    $result = array($result);
                } else {
                    $result = array();
                }
            } else {
                $result = (array) $result;
            }
        }
        ;
        if (empty($callback)) {
            $this->response->setHeader('Access-Control-Allow-Origin', '*');
            $this->response->setHeader('Access-Control-Allow-Headers', 'Authorization, Origin, X-Requested-With, Content-Type, Accept, mediaId, openid');
            $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
            $this->response->setJsonContent($result);
            !empty($contentType) && $this->response->setContentType($contentType);
        } else {
            $content = "{$callback}(" . json_encode($result) . ")";
            $this->response->setContent($content);
        }
        $this->response->send();
    }

    /**
     * exception日志记录
     * @param \Exception $e
     * @author Falcon
     */
    protected function write_exception_log(\Exception $e)
    {
        $logArray = array(
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'ip' => $this->request->getClientAddress()
        );
        $this->logger->write_log($logArray);
    }
}