<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Logo URL
    |--------------------------------------------------------------------------
    */

    'logo' => '',

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    */

    'model' => \App\Models\User::class,

    /*
    |--------------------------------------------------------------------------
    | User Username Columns - default is email and mobile
    |--------------------------------------------------------------------------
    */

    'username_columns' => [
        'email',
        'mobile',
    ],

    /*
    |--------------------------------------------------------------------------
    | User Username Columns - default is email and mobile
    |--------------------------------------------------------------------------
    */

    'mobile_default_country' => 'IR',

    /*
    |--------------------------------------------------------------------------
    | Register database Columns
    |--------------------------------------------------------------------------
    */

    'register_columns' => [
        'name' => [
            'type' => 'text',
            'validation' => [
                'required',
                'string',
                'max:250',
            ],
            'placeholder' => 'نام و نام خانوادگی خود را وارد کنید',
            'row-class' => 'col-12'
        ],
        'mobile' => [
            'type' => 'tel',
            'validation' => [
                'required',
                'unique:users',
                'numeric',
            ],
            'placeholder' => 'تلفن همراه را وارد کنید',
            'row-class' => 'col-12 col-sm-6'
        ],
        'email' => [
            'type' => 'email',
            'validation' => [
                'nullable',
                'unique:users',
                'email',
            ],
            'placeholder' => 'ایمیل را وارد کنید',
            'row-class' => 'col-12 col-sm-6'
        ],
    ],

    'verify_resend_seconds' => [
        'mobile' => 60,
        'email' => 300,
    ],

    'verify_modify_route_name' => null,

    'verify_code_len' => [
        'mobile' => 5,
        'email' => 10,
    ],

    'verify_expiration' => [
        'mobile' => "5 minutes",
        'email' => "30 minutes",
    ],

    'verify_notification' => \NovinVision\MultiStepLogin\Notifications\UserVerification::class,

];
