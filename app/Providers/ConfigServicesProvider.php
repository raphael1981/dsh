<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ConfigServicesProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        config([
            'config/view_profiles.php',
            'config/template_config.php'
        ]);
    }
}
