<?php
return [
    //本地开发测试环境下使用
    'local_default_secret' => [
        'length' => 6,
        'step' => '6239021761',
        'max' => '55884102720',
        'min' => '916132832',
        'map' => 'nKVYOEGmdivW9tUCxkfFQyZr6uX2Bepjc04l8DIAJqR7bs35LHaPgNSTwo1hMz'
    ],

    //正式环境下使用
    'default_secret' => [
        'length' => env('LJC_LENGTH', 6),
        'step' => env('LJC_STEP', ''),
        'max' => env('LJC_MAX', ''),
        'min' => env('LJC_MIN', ''),
        'map' => env('LJC_MAP', '')
    ]

];