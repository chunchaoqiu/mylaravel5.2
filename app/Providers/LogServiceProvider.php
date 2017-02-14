<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\LogService;

class LogServiceProvider extends ServiceProvider
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
        //
        $this->app->bind('mylog', function(){
            $logService = new LogService();

            return $logService;
        });

    }
}
