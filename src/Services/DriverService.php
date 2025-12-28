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

    protected static string $configPath = 'vendor/shetabit/multipay/config/payment.php';

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
        $driversPath = base_path('vendor/shetabit/multipay/src/Drivers');

        if (! File::isDirectory($driversPath)) {
            return [];
        }

        $directories = File::directories($driversPath);

        // Convert full paths into driver names (folder names)
        return collect($directories)
            ->mapWithKeys(function ($dir) {
                $name = basename($dir);

                return [mb_strtolower($name) => __(ucwords(str_replace(['-', '_'], ' ', $name)))];
            })
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
