<?php

namespace App\Providers;

use App\Http\Controllers\BaseController;
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
            $data = $view->getData();
            if (! array_key_exists('seo', $data)) {
                $view->with('seo', Seo::defaults());
            }
            // Closure routes such as /email/verify render this layout without a controller.
            if (! array_key_exists('headerCategories', $data)) {
                $view->with(BaseController::layoutData());
            }
        });
    }
}
