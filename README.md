# LaravelArtisanMakeView

Command line utility to create views in Laravel.

Requires >= Laravel 5.0

Installation
1. Add <code>"bjhansen/laravel-artisan-make-view": "dev-master"</code> to your composer.json file's <code>require-dev</code> section
2. Run <code>composer update</code>
3. Open <code>app/Console/Kernel.php</code> and add <code>\LaravelMakeView\MakeView::class,</code> to the <code>protected $commands</code> array

Usage

<code>php artisan make:view view.name --extends=layouts.app --bootstrap=bs-version --empty</code>

- <code>extends</code> is optional if you set <code>BASE_VIEW</code> in your project's .env file
    - If <code>BASE_VIEW</code> is set, but you use the <code>--extends</code> option, <code>--extends</code> takes precedence.
- <code>bootstrap</code> is optional. Preconfigures the base view with Twitter Bootstrap CSS and JS
    - <code>--bootstrap=v3</code> or <code>--bootstrap=v4</code>
- <code>empty</code> option is optional. Creates an empty view file with no layout extension.
    - When using the <code>empty</code> option all other options are ignored.
