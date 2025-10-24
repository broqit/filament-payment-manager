<?php

namespace AmidEsfahani\FilamentPaymentManager\Filament\Resources\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use AmidEsfahani\FilamentPaymentManager\Filament\Resources\PaymentGatewayResource;

class ListPaymentGateways extends ListRecords
{
    protected static string $resource = PaymentGatewayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->icon('heroicon-o-plus'),
        ];
    }
}