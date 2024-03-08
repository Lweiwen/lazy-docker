<?php

return [
    'official_account' => [
        'default' => [
            'app_id' => env('OFFICIAL_ACCOUNT_APPID', ''),
            'secret' => env('OFFICIAL_ACCOUNT_SECRET', ''),
        ]
    ],
    //微信支付服务商账号--立吉惠 的相关信息(主要是v3版本接口的公共信息)
    'wx_pay' => [
        //商户号，一般是10字节纯数字
        'mchid' => env('WXPAY_MCHID', ''),
        //商户证书序列号，APIv3必填
        'merchant_cert_serial' => env('MERCHANT_CERT_SN', ''),
        //商户证书私钥，APIv3必填
        'merchant_private_key_path' => env('MERCHANT_PVT_PATH', ''),
        //平台证书序列号，APIv3必填
        'platform_cert_serial' => env('PLATFORM_CERT_SN', ''),
        //平台证书公钥，APIv3必填
        'platform_public_key_path' => env('PLATFORM_PUB_PATH', ''),
        //APIv3密钥，在微信支付回调通知，和平台证书下载接口中使用
        'apiv3' => env('APIV3',''),
        //支付回调通知地址
        'notify_url' => env('NOTIFY_URL',''),
        //退款支付回调通知地址
        'refund_notify_url' => env('REFUND_NOTIFY_URL',''),
    ],

    //代收平台收益的微信支付普通商户号
    'platform_mchid' => env('PLATFORM_MCHID',''),

    //微信支付普通商户账号--立吉报 的相关信息(主要是v2版本接口的公共信息)
    'ljbao_wx_pay' => [
        //立吉报微信支付账号的商户号
        'mchid' => env('LJB_WXPAY_MCHID', ''),
        //立吉报微信支付账号的密钥
        'secret' => env('LJB_WXPAY_SECRET', ''),
        //立吉报微信支付证书路径
        'merchant_cert_pem_path' => env('LJB_WXPAY_CERT_PEM_PATH', ''),
        //立吉报微信支付证书密钥路径
        'merchant_key_pem_path' => env('LJB_WXPAY_KEY_PEM_PATH', '')
    ],

    //立吉惠二级商户号
    'ljh_sub_mchid' => env('LJH_SUB_MCHID', ''),

    //本机外网ip
    'out_ip' => env('OUT_IP', ''),


];