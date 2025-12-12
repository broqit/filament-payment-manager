<?php

namespace AmidEsfahani\FilamentPaymentManager\Filament\Resources\Schemas;

use AmidEsfahani\FilamentPaymentManager\Services\DriverService;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentGatewayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Basic Information'))
                    ->schema([
                        TextInput::make('name')
                            ->label('Gateway Name')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),

                        Select::make('driver')
                            ->label('Driver')
                            ->required()
                            ->options(DriverService::getAvailableDrivers())
                            ->reactive()
                            ->native(false)
                            ->searchable()
                            ->afterStateUpdated(
                                fn ($state, callable $set) => $set('config', DriverService::getDriverDefaultConfig($state))
                            )
                            ->helperText(__('Select the payment gateway driver'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make(__('Settings'))
                    ->schema([
                        Select::make('currency')
                            ->label(__('Currency'))
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->options(DriverService::currencies())
                            ->required()
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label(__('Active'))
                            ->default(true),

                        Toggle::make('is_default')
                            ->label(__('Default Gateway'))
                            ->helperText(__('Only one gateway can be default')),

                        TextInput::make('sort_order')
                            ->label(__('Order'))
                            ->numeric()
                            ->default(0)
                            ->helperText(__('Order in which gateways are displayed')),
                    ]),

                Section::make(__('Configuration'))
                    ->schema([
                        KeyValue::make('config')
                            ->label(__('Gateway Configuration'))
                            ->keyLabel(__('Config Key'))
                            ->valueLabel(__('Config Value'))
                            ->reorderable(false)
                            ->addActionLabel(__('Add Config'))
                            ->helperText(__('Configure gateway-specific settings'))
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make(__('Callback & Redirect Settings'))
                    ->schema([
                        TextInput::make('callback_url')
                            ->url()
                            ->label(__('Callback URL'))
                            ->placeholder(config('filament-payment-manager.default_callback_url'))
                            ->helperText(__('Leave empty to use default callback URL from config'))
                            ->columnSpanFull(),
                        
                        // todo:
                        // user can choose to use callback_url
                        // in callback_url user can use additional params
                        // also callback_url can be full url or route name or route url like localhost:8000/payments/callback or payments.callback or payments

                        Select::make('redirect_form_mode')
                            ->label(__('Redirect Form Mode'))
                            ->options([
                                'view' => __('Use Blade View'),
                                'html' => __('Custom HTML'),
                                'default' => __('Use Package Default'),
                            ])
                            ->default('default')
                            ->reactive()
                            ->native(false)
                            ->columnSpanFull(),

                        TextInput::make('redirect_form_view')
                            ->label(__('Blade View Path'))
                            ->placeholder('payments.redirect-form')
                            ->helperText(__('e.g., payments.redirect-form'))
                            ->visible(fn (callable $get) => $get('redirect_form_mode') === 'view'),

                        Textarea::make('redirect_form_html')
                            ->label(__('Custom HTML'))
                            ->rows(10)
                            ->helperText(__('Use {action}, {method}, and {inputs} placeholders'))
                            ->visible(fn (callable $get) => $get('redirect_form_mode') === 'html')
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Additional Information'))
                    ->schema([
                        Textarea::make('description')
                            ->label(__('Description'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}
