<?php

namespace Trysail\SDK\Laravel;

use Illuminate\Support\ServiceProvider;
use Trysail\SDK\Config;
use Trysail\SDK\TrysailClient;

class TrysailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/trysail.php',
            'trysail'
        );
        
        $this->app->singleton(TrysailClient::class, function ($app) {
            $config = new Config(
                config('trysail.base_url'),
                config('trysail.timeout', 30),
                config('trysail.verify_ssl', true)
            );
            
            return new TrysailClient($config);
        });
        
        $this->app->alias(TrysailClient::class, 'trysail');
    }
    
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/trysail.php' => config_path('trysail.php'),
            ], 'trysail-config');
        }
    }
    
    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            TrysailClient::class,
            'trysail',
        ];
    }
}