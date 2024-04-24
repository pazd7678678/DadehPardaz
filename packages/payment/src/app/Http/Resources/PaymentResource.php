<?php

namespace Pzamani\Payment\app\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Pzamani\Payment\app\Models\Payment;

/** @mixin Payment */
class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'sender'       => $this->sender->name . ' ' . $this->sender->family,
            'receiver'     => $this->receiver->name . ' ' . $this->receiver->family,
            'paytype'      => $this->paytype->name,
            'gateway_id'   => $this->gateway?->name,
            'description'  => $this->description,
            'amount'       => $this->amount,
            'iban'         => $this->iban,
            'attachment'   => $this->attachment,
            'authority'    => $this->authority,
            'reference'    => $this->reference,
            'is_confirmed' => $this->is_confirmed,
            'is_paid'      => $this->is_paid,
            'paid_at'      => $this->paid_at?->format('Y/m/d H:i:s'),
        ];
    }
}
