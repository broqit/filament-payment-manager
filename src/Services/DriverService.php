<?php

namespace AmidEsfahani\FilamentPaymentManager\Services;

use Illuminate\Support\Facades\File;
use Money\Currencies\ISOCurrencies;
use Money\Currency;

class DriverService
{
    public static function getAvailableDrivers(): array
    {
        return self::getDriverOptions();
    }
    
    public static function getDriverDefaultConfig(?string $driver): array
    {
        if (! $driver) {
            return [];
        }
    
        $cfg = config("payment.drivers.$driver");
    
        return is_array($cfg) ? $cfg : [];
    }

    protected static function getDriverOptions(): array
    {
        $drivers = config('payment.drivers', []);

        if (! is_array($drivers)) {
            return [];
        }

        return collect(array_keys($drivers))
            ->mapWithKeys(fn ($name) => [
                mb_strtolower($name) => __(ucwords(str_replace(['-', '_'], ' ', $name))),
            ])
            ->toArray();
    }

    public static function currencies()
    {
        $currencies = new ISOCurrencies;
        $list = [];

        foreach ($currencies as $currency) {
            /** @var Currency $currency */
            $list[$currency->getCode()] = $currency->getCode();
        }

        ksort($list);

        return $list;
    }
}
