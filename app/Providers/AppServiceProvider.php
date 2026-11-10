<?php

namespace App\Providers;

use App\Support\Seo;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layout.app', function ($view) {
            if (! array_key_exists('seo', $view->getData())) {
                $view->with('seo', Seo::defaults());
            }
        });
    }
}
