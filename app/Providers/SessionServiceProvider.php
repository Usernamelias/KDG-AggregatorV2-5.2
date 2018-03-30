<?php

namespace App\Providers;

use App\Extensions\AppEngineStorageSessionHandler;
use Illuminate\Support\ServiceProvider;
use Session;

class SessionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Session::extend('gae_session', function($app) {
            // Return implementation of SessionHandlerInterface...
            return new AppEngineStorageSessionHandler;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('gae_session', function($app){
            return new AppEngineStorageSessionHandler($app);
        });
    }
}
