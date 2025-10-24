<?php

namespace AmidEsfahani\FilamentPaymentManager\Filament\Resources;

use AmidEsfahani\FilamentPaymentManager\Filament\Resources\Pages\CreatePaymentGateway;
use AmidEsfahani\FilamentPaymentManager\Filament\Resources\Pages\EditPaymentGateway;
use AmidEsfahani\FilamentPaymentManager\Filament\Resources\Pages\ListPaymentGateways;
use AmidEsfahani\FilamentPaymentManager\Filament\Resources\Schemas\PaymentGatewayForm;
use AmidEsfahani\FilamentPaymentManager\Filament\Resources\Schemas\PaymentGatewayInfolist;
use AmidEsfahani\FilamentPaymentManager\Filament\Resources\Tables\PaymentGatewaysTable;
use AmidEsfahani\FilamentPaymentManager\Models\PaymentGateway;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PaymentGatewayResource extends Resource
{
    protected static ?string $model = PaymentGateway::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string | UnitEnum | null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return PaymentGatewayForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentGatewaysTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PaymentGatewayInfolist::configure($schema);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPaymentGateways::route('/'),
            'create' => CreatePaymentGateway::route('/create'),
            'edit' => EditPaymentGateway::route('/{record}/edit'),
        ];
    }
}
