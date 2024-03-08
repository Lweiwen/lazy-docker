<?php

namespace App\Exceptions;

use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Throwable;
use Dingo\Api\Routing\Helpers;

class Handler extends ExceptionHandler
{
    use Helpers;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        ApiException::class
    ];

    /**
     * 覆盖父类的$internalDontReport
     * @var array
     */
    protected $internalDontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * 覆盖父类初始化函数
     * 主要用于动态定义dontReport属性
     * Handler constructor.
     * @param Container $container
     * @author: RaysonLu
     */
    public function __construct(Container $container)
    {
        //先执行父类初始化函数
        parent::__construct($container);
        //默认不开启ApiException记录，如果开启则修改dontReport属性
        if (env('API_EXCEPTION_LOG') === true) {
            $tmp = array_search(ApiException::class, $this->dontReport);
            if ($tmp!==false)
                array_splice($this->dontReport, $tmp, 1);
        }
    }

    /**
     * 在抛出异常和错误时候，返回接口响应
     * @param \Illuminate\Http\Request $request
     * @param Throwable $exception
     * @return \Dingo\Api\Http\Response
     * @author: RaysonLu
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ApiException) {
            //针对ApiException的异常抛错，会返回抛出的code
            $code = $exception->getCode();
            if (!is_integer($code)) {
                $code = (is_numeric($code)) ? intval($code) : 0;
            }
            $message = $exception->getMessage();
        } else {
            //其他的异常和错误的，统一返回code为0
            $code = 0;
        }
        //系统的其他报错，或者报错信息为空，统一返回"系统繁忙"
        if (empty($message))
            $message = '系统繁忙';

        //api基本返回格式
        $responseData = [
            'code' => $code,
            'msg' => $message,
            'data' => []
        ];
        //开发环境下的调试模式，会把抛错信息返回出去接口
        if (env('APP_ENV') === 'local' && env('APP_DEBUG') === true) {
            $responseData['debug'] = $this->getThrowableInfo($exception);
        }
        //使用Dingo的response返回信息
        return $this->response->array($responseData)->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'Authorization, Origin, X-Requested-With, Content-Type, Accept')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
    }

    /**
     * 获取一个抛错对象的基本信息，用于调试api接口信息返回
     * @param Throwable $e
     * @return array
     * @author: RaysonLu
     */
    public function getThrowableInfo(Throwable $e)
    {
        return [
            'throwable_type' => get_class($e),
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTrace()
        ];
    }

    /**
     * 对所有的抛错进行选择性日志记录处理
     * @param Throwable $exception
     * @return void
     * @author: RaysonLu
     */
    public function report(Throwable $exception)
    {
        //检测抛错是否设置了不需要进行日志记录
        if ($this->shouldntReport($exception)) {
            return;
        }

        //如果开启了，ApiException的报错，额外记录一个地方
        if ($exception instanceof ApiException) {
            Log::channel('api_exception')->log(
                'debug',
                $exception->getMessage(),
                ['exception' => $exception]
            );
            return;
        }
        //记录除了ApiException，系统的其他报错，此处的报错记录应该认真检阅并处理
        Log::channel('other_error')->log(
            'debug',
            $exception->getMessage(),
            ['exception' => $exception]
        );
    }
}
