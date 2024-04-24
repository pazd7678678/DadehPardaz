<?php

namespace Pzamani\Payment\app\Http\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Pzamani\Auth\app\Models\User;
use Pzamani\Payment\app\Models\Payment;

class PayAction
{
    public function run(int $payTypeId, string $senderId, string $nationalcode, string $description, int $amount, string $iban, ?UploadedFile $attachment): Payment
    {
        $receiverId = User::query()->where('nationalcode', $nationalcode)->value('id');
        $fileName = null;
        if ($attachment) {
            $fileName = Str::uuid()->toString() . '.' . $attachment->extension();
            Storage::put('payments/' . $fileName, $attachment);
        }
        return Payment::query()->create([
            'sender_id'   => $senderId,
            'receiver_id' => $receiverId,
            'paytype_id'  => $payTypeId,
            'description' => $description,
            'amount'      => $amount,
            'iban'        => $iban,
            'attachment'  => $fileName,
        ]);
    }
}
