<?php

namespace PayKit\Laravel;

use Illuminate\Support\ServiceProvider;
use PayKit\Manager\GatewayManager;
use PayKit\Manager\GatewayRegistry;
use PayKit\Manager\DriverResolver;

final class PayKitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GatewayRegistry::class, fn() => new GatewayRegistry());

        $this->app->singleton(DriverResolver::class, fn($app) => new DriverResolver($app->make(GatewayRegistry::class)));

        $this->app->singleton(GatewayManager::class, fn($app) => new GatewayManager(
            $app->make(GatewayRegistry::class),
        ));
    }
}