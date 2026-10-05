<?php

namespace App\Providers;

use App\Order;
use App\Service;
use App\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        \Schema::defaultStringLength(191);
        if (env('HTTPS_MODE') === 'ON') {
            $this->app['url']->forceScheme('https');
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      

        view()->composer(['front.*'], function ($view) {
            $settings = Setting::find(1);
            $settings = $settings->settings ?? [];
            $view->with('_settings', $settings);
        });
    }

}
