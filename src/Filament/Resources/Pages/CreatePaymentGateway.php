<?php

namespace AmidEsfahani\FilamentPaymentManager\Filament\Resources\Pages;

use AmidEsfahani\FilamentPaymentManager\Filament\Resources\PaymentGatewayResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentGateway extends CreateRecord
{
    protected static string $resource = PaymentGatewayResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Clean up based on redirect form mode
        $mode = $data['redirect_form_mode'] ?? 'default';

        if ($mode !== 'view') {
            $data['redirect_form_view'] = null;
        }

        if ($mode !== 'html') {
            $data['redirect_form_html'] = null;
        }

        // Remove temporary field
        unset($data['redirect_form_mode']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Payment gateway created successfully';
    }
}
