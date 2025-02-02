<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Modules\Auth\app\Observers\UserObserver;
use Modules\Properties\App\Models\Property;
use Modules\Properties\app\Observers\PropertyObserver;
use Modules\Properties\App\Policies\PropertyPolicy;
use Modules\Transactions\App\Models\PropertyTransaction;
use Modules\Transactions\app\Observers\PropertyTransactionObserver;

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
        User::observe(UserObserver::class);

        Property::observe(PropertyObserver::class);
        Gate::define('delete', [PropertyPolicy::class, 'delete']);

        PropertyTransaction::observe(PropertyTransactionObserver::class);

    }
}
