<?php

return [
    /**
     *  权限名称
     *  说明：
     *      1、根key代表登录认证路径，对应的值是一个数组，代表每个端(路径)对应的权限；
     *      2、每个端(路径)对应的权限数组中，
     *          1)key表示：该权限在数据库中，对应的端(路径)的成员表里面，权限字段的值
     *          2)value表示：该权限在项目中定义的名称，在登录、权限判断、前后端交流中，使用该名称；
     */
    'role_name' => [
        //总后台
        'backstage' => [
            //总后台超级管理员
            1 => 'backstage_supper_admin',
            //总后台管理员
            2 => 'backstage_admin',
            //总后台主管
            3 => 'backstage_manager',
            //总后台业务员
            4 => 'backstage_salesman',
            //总后台财务
            5 => 'backstage_cashier',
            //总后台核价员
            6 => 'backstage_checker'
        ],
        //商家后台
        'business' => [
            //总管
            1 => 'business_supper_admin',
            //管理
            2 => 'business_admin',
            //商家店长
            3 => 'business_shop_manager',
            //商家员工
            4 => 'business_staff',
            //跟进业务员(虚拟)
            99=> 'provisional_salesman',
        ],
        //分站后台
        'substation' => [
            //站长
            1 => 'site_admin',
            //站点主管
            2 => 'site_manager',
            //站点业务员
            3 => 'site_salesman',
            //财务
            4 => 'site_cashier',
            //核价员
            5 => 'site_checker'
        ],
        'web' => [],
        //web临时登录
        'silence' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'ljh_guard',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
            'hash' => false,
        ],
        'ljh_guard' => [
            'driver' => 'ljh_jwt'       //使用Auth服务提供者(AuthServiceProvider)中额外注册的登陆守护器ljh_jwt
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => 10800,

];
