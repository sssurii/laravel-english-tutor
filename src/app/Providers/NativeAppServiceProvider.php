<?php

namespace App\Providers;

use Native\Laravel\Facades\Window;
use Illuminate\Support\ServiceProvider;

class NativeAppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Window::open()
            ->title('English Tutor')
            ->width(1200)
            ->height(800)
            ->minWidth(800)
            ->minHeight(600)
            ->resizable()
            ->url('/');
    }
}

