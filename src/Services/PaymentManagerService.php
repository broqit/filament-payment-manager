<?php

namespace AmidEsfahani\FilamentPaymentManager\Services;

use Illuminate\Support\Facades\Config;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;
use AmidEsfahani\FilamentPaymentManager\Models\PaymentGateway;

class PaymentManagerService
{
    public function __construct()
    {
        $this->loadGatewaysToConfig();
    }

    /**
     * Load all active gateways from database to config
     */
    protected function loadGatewaysToConfig(): void
    {
        $gateways = PaymentGateway::active()->get();

        foreach ($gateways as $gateway) {
            $this->registerGateway($gateway);
        }

        // Set default gateway
        $defaultGateway = PaymentGateway::default()->active()->first();
        if ($defaultGateway) {
            Config::set('payment.default', $defaultGateway->driver);
        }
    }

    /**
     * Register a single gateway to the config
     */
    public function registerGateway(PaymentGateway $gateway): void
    {
        $config = $gateway->getConfigWithDefaults();
        
        // Override callback URL if set
        if ($gateway->callback_url) {
            $config['callbackUrl'] = $gateway->callback_url;
        }

        Config::set("payment.drivers.{$gateway->driver}", $config);
    }

    /**
     * Create a payment
     */
    public function createPayment(float $amount, ?string $driver = null): \Shetabit\Multipay\Payment
    {
        if (!$driver) {
            $gateway = PaymentGateway::default()->active()->firstOrFail();
            $driver = $gateway->driver;
        } else {
            $gateway = PaymentGateway::where('driver', $driver)->active()->firstOrFail();
        }

        // Ensure gateway is registered
        $this->registerGateway($gateway);

        return Payment::via($driver)->amount($amount);
    }

    /**
     * Get custom redirect form HTML
     */
    public function getRedirectForm(PaymentGateway $gateway, string $action, string $method, array $inputs): string
    {
        // Check if custom HTML is set
        if ($gateway->redirect_form_html) {
            return $this->parseCustomHtml($gateway->redirect_form_html, $action, $method, $inputs);
        }

        // Check if custom view is set
        if ($gateway->redirect_form_view) {
            return view($gateway->redirect_form_view, [
                'action' => $action,
                'method' => $method,
                'inputs' => $inputs,
            ])->render();
        }

        // Use default package view
        return view('filament-payment-manager::redirect-form', [
            'action' => $action,
            'method' => $method,
            'inputs' => $inputs,
        ])->render();
    }

    /**
     * Parse custom HTML with placeholders
     */
    protected function parseCustomHtml(string $html, string $action, string $method, array $inputs): string
    {
        $inputsHtml = '';
        foreach ($inputs as $name => $value) {
            $inputsHtml .= sprintf(
                '<input type="hidden" name="%s" value="%s">',
                htmlspecialchars($name),
                htmlspecialchars($value)
            );
        }

        $replacements = [
            '{action}' => htmlspecialchars($action),
            '{method}' => strtoupper($method),
            '{inputs}' => $inputsHtml,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $html);
    }

    /**
     * Verify payment
     */
    public function verifyPayment(?string $driver = null)
    {
        if (!$driver) {
            $gateway = PaymentGateway::default()->active()->firstOrFail();
            $driver = $gateway->driver;
        } else {
            $gateway = PaymentGateway::where('driver', $driver)
                ->active()
                ->firstOrFail();
        }

        $this->registerGateway($gateway);

        return Payment::via($driver)->verify();
    }

    /**
     * Get all active gateways
     */
    public function getActiveGateways(): \Illuminate\Database\Eloquent\Collection
    {
        return PaymentGateway::active()
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get default gateway
     */
    public function getDefaultGateway(): ?PaymentGateway
    {
        return PaymentGateway::default()->active()->first();
    }

    /**
     * Get gateway by driver
     */
    public function getGatewayByDriver(string $driver): ?PaymentGateway
    {
        return PaymentGateway::where('driver', $driver)
            ->active()
            ->first();
    }
}