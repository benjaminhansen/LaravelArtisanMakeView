# Laravel Artisan Make View

Command line utility to create views in Laravel.

!! Overrides Laravel's default <code>make:view</code> command.

Requires >= Laravel 5.0

<code>composer require benjaminhansen/laravel-artisan-make-view</code>

Configuration < Laravel 5.5
1. Open <code>app/Console/Kernel.php</code> and add <code>BenjaminHansen\LaravelMakeView\MakeView::class,</code> to the <code>protected $commands</code> array

##Usage

<code>php artisan make:view view.name --extends=layouts.app --bootstrap=bs-version --empty --resourceful</code>

- <code>resourceful</code> used to create a view directory from <code>view.name</code> and then resourceful view files <code>index.blade.php</code>, <code>create.blade.php</code>, <code>show.blade.php</code>, and <code>edit.blade.php</code>

- <code>extends</code> is optional if you set <code>BASE_VIEW</code> in your project's .env file
    - If <code>BASE_VIEW</code> is set, but you use the <code>--extends</code> option, <code>--extends</code> takes precedence.

- <code>bootstrap</code> is optional. Preconfigures the base view with Twitter Bootstrap CSS and JS
    - <code>--bootstrap=v3</code> or <code>--bootstrap=v4</code>

- <code>empty</code> option is optional. Creates an empty view file with no layout extension.
    - When using the <code>empty</code> option all other options are ignored.
