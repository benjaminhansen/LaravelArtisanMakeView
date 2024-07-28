<?php

namespace BenjaminHansen\LaravelMakeView\Providers;

use Illuminate\Support\ServiceProvider;
use BenjaminHansen\LaravelMakeView\MakeView;

class MakeViewProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            MakeView::class,
        ]);
    }

    public function isDeferred()
    {
        //
    }

    public function register()
    {
        //
    }
}
