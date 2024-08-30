# Laravel Artisan Make View

An extended replacement for Laravel's built-in <code>make:view</code> command.

Requires >= Laravel 5.0

```
composer require benjaminhansen/laravel-artisan-make-view
```

Configuration < Laravel 5.5
Open <code>app/Console/Kernel.php</code> and add <code>BenjaminHansen\LaravelMakeView\MakeView::class,</code> to the <code>protected $commands</code> array

## Usage

<code>php artisan make:view view.name --extends=layouts.app --uses=bootstrap5/tailwind --empty --resourceful --suffix=something.php</code>

- <code>resourceful</code> used to create a view directory from <code>view.name</code> and then resourceful view files <code>index.blade.php</code>, <code>create.blade.php</code>, <code>show.blade.php</code>, and <code>edit.blade.php</code>

- <code>extends</code> is optional if you set <code>BASE_VIEW</code> in your project's .env file
    - If <code>BASE_VIEW</code> is set, but you use the <code>--extends</code> option, <code>--extends</code> takes precedence.

- <code>uses</code> is optional. Preconfigures the base view with Twitter Bootstrap CSS and JS or Tailwind CSS
    - <code>--uses=bootstrap5</code> or <code>--uses=tailwind</code>

- <code>empty</code> option is optional. Creates an empty view file with no layout extension.
    - When using the <code>empty</code> option all other options are ignored.

- <code>suffix</code> is optional if you want to override the default blade.php file suffix with something else.
