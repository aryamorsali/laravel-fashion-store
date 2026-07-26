<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',

    // index size
    'index-image-sizes' => [

        'large' => [
            'width' => 1200,
            'height' => 1485,
        ],

        'main' => [
            'width' => 350,
            'height' => 434,
        ],

        'small' => [
            'width' => 120,
            'height' => 150,
        ],
    ],

    'default-current-index-image' => 'main',


    'blog-image-sizes' => [

        'cover' => [
            'width'  => 1600,
            'height' => 1000,

        ],

        'thumb' => [
            'width' => 300,
            'height' => 366,
        ],

    ],
    'default-current-blog-image' => 'cover',



    // cache size
    'cache-image-sizes' => [
        'large' => [
            'width' => 700,
            'height' => 868
        ],
        'medium' => [
            'width' => 270,
            'height' => 334
        ],
        'small' => [
            'width' => 80,
            'height' => 60
        ],
    ],

    'default-current-cache-image' => 'medium',
    'image-cache-life-time' => 10,
    'image-not-found' => '',

];
