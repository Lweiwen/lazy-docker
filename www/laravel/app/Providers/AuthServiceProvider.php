<?php

namespace App\Providers;

use App\Services\AuthLogin\AuthGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //注册自定义的登录守护器
//        Auth::extend('ljh_jwt',function ($app,$name,array $config){
//            return new AuthGuard($app['request']->header(),$app['config']['auth']['role_name']);
//        });
    }
}
