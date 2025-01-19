<?php

namespace App\Providers;

use App\Observers\PropertyObserver;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Modules\Properties\App\Models\Property;

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
        $langPath = base_path('lang'); // Adjust the path if necessary

        Lang::addNamespace('custom', $langPath);

        if ($this->app->environment('local')) {
            Telescope::filter(function (IncomingEntry $entry) {
                return true;  // Allow all entries in local
            });
        }

        Property::observe(PropertyObserver::class);

    }
}
