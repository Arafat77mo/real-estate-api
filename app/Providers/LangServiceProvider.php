<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Lang;

class LangServiceProvider extends ServiceProvider
{
    /**
     * Register the application's services.
     *
     * @return void
     */
    public function register()
    {
        // No registration needed for custom lang directory
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Add the custom language path
        Lang::addNamespace('custom', base_path('lang'));  // Path to custom lang folder
    }
}
