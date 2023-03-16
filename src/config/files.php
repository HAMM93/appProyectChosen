<?php

return [

    /*--------------------------------------------------------------------------
    | Size
    |--------------------------------------------------------------------------
    |
    | This file configures sizes of files
    |
    */

    'size' => [
        'min' => env('FILE_SIZE_MIN', 104), //(bytes)1048576
        'max' => env('FILE_SIZE_MAX', 15360000), //(bytes)
    ],

    /*
    |--------------------------------------------------------------------------
    | Dimensions
    |--------------------------------------------------------------------------
    |
    | This file configures dimensions of files
    |
    */
    'dimensions' => [
        'max' => [
            'width' => env('FILE_DIMENSION_WIDTH', 5000), //(pixels)
            'height' => env('FILE_DIMENSION_HEIGHT', 5000), //(pixels)
        ],
        //TODO :: VERIFICAR DIMENSÕES MINIMAS VIÁVEIS
        'min' => [
            'width' => env('FILE_DIMENSION_WIDTH', 500), //(pixels)
            'height' => env('FILE_DIMENSION_HEIGHT', 500), //(pixels)
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Extensions
    |--------------------------------------------------------------------------
    |
    | This file configures extensions of files
    |
    */
    'extensions_allowed' => [
        'image/png',
        'image/jpg',
        'image/jpeg'
    ],

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    |
    | This file configures paths of files in S3 Bucket
    |
    */
    'paths_s3' => [
        'zip_donor' => 'donors/zips',
        'donor_photo' => 'donors/photo',
        'zip_children' => 'children/zip'
    ]
];
