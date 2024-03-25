<?php

namespace LaravelMakeView\Providers;

use LaravelMakeView\MakeView;

class MakeViewProvider
{
    public function boot()
    {
        $this->commands([
            MakeView::class,
        ]);
    }
}