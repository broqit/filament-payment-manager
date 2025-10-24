<?php

namespace AmidEsfahani\FilamentPaymentManager\Filament\Resources\Pages;

use AmidEsfahani\FilamentPaymentManager\Filament\Resources\PaymentGatewayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaymentGateway extends EditRecord
{
    protected static string $resource = PaymentGatewayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Determine redirect form mode from existing data
        if (! empty($data['redirect_form_view'])) {
            $data['redirect_form_mode'] = 'view';
        } elseif (! empty($data['redirect_form_html'])) {
            $data['redirect_form_mode'] = 'html';
        } else {
            $data['redirect_form_mode'] = 'default';
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
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

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Payment gateway updated successfully';
    }
}
