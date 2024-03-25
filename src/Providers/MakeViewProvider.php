<?php

namespace LaravelMakeView\Providers;

use Illuminate\Support\ServiceProvider;
use LaravelMakeView\MakeView;

class MakeViewProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            MakeView::class,
        ]);
    }

    public static function isDeferred()
    {
        //
    }

    public function register()
    {
        //
    }
}
