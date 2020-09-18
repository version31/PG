<?php

use Hekmatinasser\Verta\Facades\Verta;

return [
    'models' => [
        'App\Models\Service' => 'خدمات',
        'App\Service' => 'خدمات',
        'App\Models\Product' => 'محصول',
        'App\Product' => 'محصول',
    ],

    'media' => [
        'type' => [
            "picture" => "تصویر",
            "video" => "ویدیو",
        ],
    ],

    'products' => [
        'status' => [
            "0" => "غیر فعال",
            "1" => "فعال",
        ],

    ],

    'storyables' => [
        'status' => [
            0 => 'استوری تازه',
            1 => 'استوری تایید شده',
            2 => 'ثبت توسط ادمین',
            -1 => 'غیر فعال توسط ادمین',
        ],

    ],


    "payment" => [
        "status" => [
            'INIT' => 'پرداخت اولیه',
            'FAILED' => 'ناموفق / انصراف',
            'SUCCEED' => 'موفق',
        ],
    ],


    "users" => [
        "gender" => [
            'male' => 'آقا',
            'Male' => 'آقا',
            'MALE' => 'آقا',
            'female' => 'خانم',
            'Female' => 'خانم',
            'FEMALE' => 'خانم',
        ],
    ],


    "plans" => [
        "type" => [
            "BUY_PROVIDER_PLAN" => "پروفایل تجاری",
            "EXTEND_PROVIDER_PLAN" => "تمدید پروفایل تجاری",
            "BUY_PROVIDER_STAR" => "ستاره",
            "BUY_STORY" => "استوری",
            "BUY_PROMOTE_PRODUCT" => "ارتقا محصول",
            "INCREASE_INSERT_PRODUCT" => "بسته محصول",
        ]
    ]
];
