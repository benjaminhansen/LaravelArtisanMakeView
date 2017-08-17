# LaravelArtisanMakeView

Installation
1. Add <code>"bjhansen/laravel-artisan-make-view": "dev-master"</code> to your composer.json file's <code>require-dev</code> section
2. Run <code>composer update</code>
3. Open <code>app/Console/Kernel.php</code> and add <code>\LaravelMakeView\MakeView::class,</code> to the <code>protected $commands</code> array

Usage

<code>php artisan make:view view.name --extends=layouts.app</code>

- <code>extends</code> option is optional if you set <code>BASE_VIEW</code> in your project's .env file