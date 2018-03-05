<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SiteConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['backend.*','frontend.*'],'App\Http\ViewComposers\SiteConfigComposer');
        view()->composer(['backend.*'],'App\Http\ViewComposers\PanelConfigComposer');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
