<?php

namespace App\Providers;

use App\Services\Base62Service;
use App\Services\Database\DBShortUrlViewService;
use App\Services\ShortUrlViewService;
use App\Services\Snowflake\SnowflakeTokenService;
use App\Services\TokenService;
use App\Services\PrometheusService;
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
        $this->app->singleton(ShortUrlViewService::class, function($app) {
            return new DBShortUrlViewService();
        });

        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        # Prometheus
        $this->app->singleton(PrometheusService::class, PrometheusService::class);
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
