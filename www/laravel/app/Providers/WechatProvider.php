<?php

namespace App\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class WechatProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->singleton('wx_official_account',function (){
            return  \EasyWeChat\Factory::officialAccount(config('wechat.official_account.default'));
        });
    }



    public function provides()
    {
        return ['wx_official_account'];
    }
}