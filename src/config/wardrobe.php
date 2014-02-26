<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Themes Directory
    |--------------------------------------------------------------------------
    |
    | Set this to the directory where your themes will be located in your public
    | folder.
    |
    */
    'theme_dir' => 'themes',

    /*
    |--------------------------------------------------------------------------
    | Image Storage
    |--------------------------------------------------------------------------
    |
    | Set this to indicate how to store uploaded imates. Values are 'filesystem'
    | or 's3'. If s3 is indicated, set your credentials below.
    |
    */
    'image_storage' => 'filesystem',

    /*
    |--------------------------------------------------------------------------
    | Image Uploads Directory
    |--------------------------------------------------------------------------
    |
    | Set this to the directory where your images will be located in your public
    | folder.
    |
    */
    'image_dir' => 'img',

    /*
    |--------------------------------------------------------------------------
    | Image Resize
    |--------------------------------------------------------------------------
    |
    | If enabled, images will be resized automatically to fit within the
    | specified width/height (pixels) when uploaded.
    |
    */
    'image_resize' => array(
        'enabled'       => false,
        'width'         => '600',
        'height'        => '600',
    ),

    /*
    |--------------------------------------------------------------------------
    | Image S3 Creds
    |--------------------------------------------------------------------------
    |
    | If using S3 as the image storage, specify your S3 credentials here.
    |
    */
    'image_s3_creds' => array(
        'bucket'       => 'bucket',
        'api_key'      => 'key',
        'api_secret'   => 'secret',
    ),

    /*
    |--------------------------------------------------------------------------
    | 404 Handling
    |--------------------------------------------------------------------------
    |
    | Set this to true if you want wardrobe to handle your 404 errors
    | gracefully.
    |
    */
    'handles_404' => false,

    /*
    |--------------------------------------------------------------------------
    | Active Theme
    |--------------------------------------------------------------------------
    |
    | Set this to the directory of the theme you want active. No slashes.
    |
    */
    'theme' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Site Title
    |--------------------------------------------------------------------------
    |
    | Set this to your sites title
    |
    */
    'title' => 'Blog',

    /*
    |--------------------------------------------------------------------------
    | Posts per page
    |--------------------------------------------------------------------------
    |
    | Set this to the number of posts you want per page.
    |
    */
    'per_page' => 5,

    /*
    |--------------------------------------------------------------------------
    | Installed
    |--------------------------------------------------------------------------
    |
    | This sets a flag so that it can only be installed once.
    |
    */
    'installed' => true,

	/*
	|--------------------------------------------------------------------------
	| Enable Cache
	|--------------------------------------------------------------------------
	|
	| Set this to true to enable caching. If true it will then use the
	| default laravel cache setup.
	|
	*/
	'cache' => null,

    /*
    |--------------------------------------------------------------------------
    | In Framework
    |--------------------------------------------------------------------------
    |
    | Checks if this is installed in wardrobe/wardrobe
    |
    */
    'in_framework' => false,

    /*
    |--------------------------------------------------------------------------
    | Other View Directories
    |--------------------------------------------------------------------------
    |
    | Any other view directories you may want loaded for Wardrobe to work
    |
    */
    'view_dirs' => array(),
);
