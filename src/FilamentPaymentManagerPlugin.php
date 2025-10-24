<?php

namespace AmidEsfahani\FilamentPaymentManager;

use Filament\Panel;
use Filament\Contracts\Plugin;
use AmidEsfahani\FilamentPaymentManager\Filament\Resources\PaymentGatewayResource;

class FilamentPaymentManagerPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-payment-manager';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                PaymentGatewayResource::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }
}
