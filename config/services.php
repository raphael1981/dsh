<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'domains' => [
        'admin' => 'admindsh.dsh.usermd.net',
        'customers' => 'dsh.waw.pl'
    ],
    'adminmail'=>'newsletter@dsh.usermd.net',
    'category_svg_folder'=>'categories_icons',
    'default_template_color'=>[
        'rgb'=>'FF5B3B',
        'classname'=>'outrageous-orange-color'
    ],
    'template_colors'=>[
        [
            'rgb'=>'E3B5A4',
            'classname'=>'shilo-color',
            'bgrgb'=>'f5f5f5',
            'footbg'=>'f5f5f5',
            'activergb'=>'ff5b3b'
        ],
        [
            'rgb'=>'60BCB0',
            'classname'=>'puerto-rico-color',
            'bgrgb'=>'f5f5f5',
            'footbg'=>'f5f5f5',
            'activergb'=>'ff5b3b'
        ],
        [
            'rgb'=>'FF5B3B',
            'classname'=>'outrageous-orange-color',
            'bgrgb'=>'f5f5f5',
            'footbg'=>'f5f5f5',
            'activergb'=>'60bcb0'
        ],
        [
            'rgb'=>'F79D5C',
            'classname'=>'sandy-brown-color',
            'bgrgb'=>'f5f5f5',
            'footbg'=>'f5f5f5',
            'activergb'=>'ff5b3b'
        ],
        [
            'rgb'=>'773344',
            'classname'=>'merlot-color',
            'bgrgb'=>'f5f5f5',
            'footbg'=>'f5f5f5',
            'activergb'=>'ff5b3b'
        ],
        [
            'rgb'=>'2EA7FF',
            'classname'=>'dodger-blue-color',
            'bgrgb'=>'D6E1EE',
            'footbg'=>'EDEDED',
            'activergb'=>'ff5b3b'
        ],
        [
            'rgb'=>'018187',
            'classname'=>'teal-color',
            'bgrgb'=>'f5f5f5',
            'footbg'=>'EDEDED',
            'activergb'=>'ff5b3b'
        ],
        [
            'rgb'=>'FFBD21',
            'classname'=>'sunglow-color',
            'bgrgb'=>'FCF5E6',
            'footbg'=>'FCF5E6',
            'activergb'=>'ff5b3b'
        ]

    ],
    'template_config'=>[
        'is_many'=>[
            [
                'name'=>'Dwie kolumny',
                'show'=>'double'
            ],
            [
                'name'=>'Trzy kolumny',
                'show'=>'trio'
            ]
        ]
    ],
    'mimeicons'=>[
        [
            'icon'=>'/mime_icons/blank-file.svg',
            'mimelist'=>[

            ]
        ],
        [
            'icon'=>'/mime_icons/blank-file.svg',
            'mimelist'=>[
                'audio/mpeg',
                'audio/x-wav'
            ]
        ],
        [
            'icon'=>'/mime_icons/blank-file.svg',
            'mimelist'=>[
                'video/mp4'
            ]
        ],
        [
            'icon'=>'/mime_icons/blank-file.svg',
            'mimelist'=>[
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/svg+xml'
            ]
        ],
        [
            'icon'=>'/mime_icons/blank-file.svg',
            'mimelist'=>[
                'text/plain',
                'application/msword',
                'application/vnd.oasis.opendocument.text',
                'text/rtf',
                'application/pdf',
                'application/msword'
            ]
        ],
        [
            'icon'=>'/mime_icons/blank-file.svg',
            'mimelist'=>[
                'application/vnd.oasis.opendocument.spreadsheet',
                'application/octet-stream',
                'application/vnd.ms-office',
                'application/octet-stream'
            ]
        ],
        [
            'icon'=>'/mime_icons/blank-file.svg',
            'mimelist'=>[
                'application/x-7z-compressed',
                'application/x-rar',
                'application/zip'
            ]
        ]
    ],
    'youtube'=>[
        'OAUTH2_CLIENT_ID'=>'932820321142-epa7rquimi7i95kg9jp79474ms62s19j.apps.googleusercontent.com',
        'OAUTH2_CLIENT_SECRET'=>'yPwmckdkyiGTX6iICE5_z188'
    ],
    'leadscene'=>[
        'three'=>'leadthree.json',
        'one'=>'leadone.json'
    ]

];
