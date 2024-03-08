<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        //注册option请求路由(此部分可以优化到请求处理中间件)
        Route::options(
            '/{anything}', function(){
            return response('',200)
                ->withHeaders([
                    'Access-Control-Allow-Origin'=> '*',
                    'Access-Control-Allow-Headers' => 'Authorization, Origin, X-Requested-With, Content-Type, Accept',
                    'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE',
                    'Content-Type' => 'application/json'
                ]);
        })->where('anything', '.*');

        //注册api类路由
        $this->mapApiRoutes();

        //不需要web端路由
        //$this->mapWebRoutes();

        //注册404路由
        Route::match(
            ['GET','POST','PUT','DELETE'],
            '/{anything}', function(){
            return response('This is a way without a way',404);
        })->where('anything', '.*')->fallback();


    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
