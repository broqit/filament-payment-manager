<?php

namespace AmidEsfahani\FilamentPaymentManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class PaymentGateway extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'driver',
        'currency',
        'is_active',
        'is_default',
        'config',
        'callback_url',
        'redirect_form_view',
        'redirect_form_html',
        'sort_order',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'config' => AsArrayObject::class,
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        // Ensure only one default gateway
        static::saving(function (PaymentGateway $gateway) {
            if ($gateway->is_default) {
                static::where('id', '!=', $gateway->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function getCallbackUrlAttribute($value): string
    {
        return $value ?? config('filament-payment-manager.default_callback_url', url('/payment/callback'));
    }

    public function getConfigWithDefaults(): array
    {
        $defaults = config("filament-payment-manager.driver_defaults.{$this->driver}", []);
        $config = $this->config ? (array) $this->config : [];
        
        return array_merge($defaults, $config);
    }
}