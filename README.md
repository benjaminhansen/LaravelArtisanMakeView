# LaravelArtisanMakeView

Command line utility to create views in Laravel.

Requires >= Laravel 5.0

Installation
1. Add <code>"bjhansen/laravel-artisan-make-view": "dev-master"</code> to your composer.json file's <code>require-dev</code> section
2. Run <code>composer update</code>
3. Open <code>app/Console/Kernel.php</code> and add <code>\LaravelMakeView\MakeView::class,</code> to the <code>protected $commands</code> array

Usage

<code>php artisan make:view view.name --extends=layouts.app --bootstrap=bs-version</code>

- <code>extends</code> option is optional if you set <code>BASE_VIEW</code> in your project's .env file
- <code>bootstrap</code> option is optional. Preconfigures the base view with Twitter Bootstrap CSS and JS
    - <code>--bootstrap=v3</code> or <code>--bootstrap=v4</code> 
