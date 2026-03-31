<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Content Disk
    |--------------------------------------------------------------------------
    |
    | The filesystem disk where your markdown content files are stored.
    | Configure this disk in config/filesystems.php — point it to your
    | content directory (or SFTP mount for instant publishing).
    |
    */

    'disk' => env('PAPER_PI_DISK', 'docs'),

    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    |
    | Locale codes your content supports. Used in URL routing and file lookups.
    | Single-language sites can leave this as ['en'].
    |
    */

    'locales' => ['en'],

    /*
    |--------------------------------------------------------------------------
    | Default Locale
    |--------------------------------------------------------------------------
    */

    'default_locale' => env('PAPER_PI_DEFAULT_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | auto_load_routes: Set to true (or PAPER_PI_AUTO_LOAD_ROUTES=true in .env)
    |                   to have the package register its routes automatically.
    |
    | route_prefix:     URL prefix for content. e.g. 'stories', 'docs', 'blog'
    |
    | route_structure:  'prefix-first' → /{prefix}/{locale}/{path}
    |                   'locale-first' → /{locale}/{prefix}/{path}
    |
    */

    'auto_load_routes' => env('PAPER_PI_AUTO_LOAD_ROUTES', false),

    'route_prefix' => env('PAPER_PI_ROUTE_PREFIX', 'stories'),

    'route_structure' => env('PAPER_PI_ROUTE_STRUCTURE', 'prefix-first'),

    'route_middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Edit Link
    |--------------------------------------------------------------------------
    |
    | Show an "Edit on GitHub" link on each page. Set repository to null
    | to disable.
    |
    */

    'edit_link' => [
        'repository' => env('PAPER_PI_EDIT_REPOSITORY', null),
        'branch'     => env('PAPER_PI_EDIT_BRANCH', 'main'),
        'path'       => env('PAPER_PI_EDIT_PATH', 'content'),
    ],

    /*
    |--------------------------------------------------------------------------
    | View Configuration
    |--------------------------------------------------------------------------
    |
    | Publish views with: php artisan vendor:publish --tag=paper-pi-views
    | Then customise resources/views/vendor/paper-pi/ freely.
    |
    */

    'views' => [
        'layout' => 'paper-pi::layout',
        'page'   => 'paper-pi::page',
    ],

];
