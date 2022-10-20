<?php

namespace App\Providers;

use App\Services\Base62Service;
use App\Services\Snowflake\SnowflakeTokenService;
use App\Services\TokenService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Base62Service::class, function($app) {
            return new Base62Service();
        });
        $this->app->singleton(TokenService::class, function($app) {
            return new SnowflakeTokenService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
