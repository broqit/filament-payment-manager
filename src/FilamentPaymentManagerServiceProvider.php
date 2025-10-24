<?php

namespace AmidEsfahani\FilamentPaymentManager;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use AmidEsfahani\FilamentPaymentManager\Livewire\ConfigJsonEditor;

class FilamentPaymentManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/filament-payment-manager.php',
            'filament-payment-manager'
        );
    }

    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-payment-manager');

        // Register Livewire components
        // Livewire::component('config-json-editor', ConfigJsonEditor::class);

        // Publish config
        $this->publishes([
            __DIR__ . '/../config/filament-payment-manager.php' => config_path('filament-payment-manager.php'),
        ], 'filament-payment-manager-config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'filament-payment-manager-migrations');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/filament-payment-manager'),
        ], 'filament-payment-manager-views');

        // Register assets
        // FilamentAsset::register([
        //     Css::make('filament-payment-manager', __DIR__ . '/../resources/css/filament-payment-manager.css'),
        // ], 'your-vendor/filament-payment-manager');
    }
}