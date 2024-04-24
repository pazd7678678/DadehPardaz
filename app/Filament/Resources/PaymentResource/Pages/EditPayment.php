<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Http\Services\NotifyWithEmail;
use App\Http\Services\NotifyWithSms;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Pzamani\Payment\app\Models\Payment;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var Payment $record */
        if ($record->is_confirmed) {
            app(NotifyWithSms::class)->notify('09' . $record->sender->mobile, 'You can pay order ' . $record->id);
            app(NotifyWithEmail::class)->notify('09' . $record->sender->email, 'You can pay order ' . $record->id);
        }
        return parent::handleRecordUpdate($record, $data);
    }
}
