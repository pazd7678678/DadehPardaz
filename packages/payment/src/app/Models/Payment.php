<?php

namespace Pzamani\Payment\app\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Pzamani\Auth\app\Models\User;

/**
 * @package Pzamani\Payment\app\Models
 *
 * @property string $id
 * @property string $sender_id
 * @property string $receiver_id
 * @property int $paytype_id
 * @property int $gateway_id
 * @property string $description
 * @property int $amount
 * @property string $iban
 * @property string $attachment
 * @property string $authority
 * @property string $reference
 * @property bool $is_confirmed
 * @property bool $is_paid
 * @property Carbon $paid_at
 *
 * @property Gateway $gateway
 * @property Paytype $paytype
 * @property User $receiver
 * @property User $sender
 */
class Payment extends Model
{
    use HasUuids;

    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'payments';
    protected $fillable = [
        'id',
        'sender_id',
        'receiver_id',
        'paytype_id',
        'gateway_id',
        'description',
        'amount',
        'iban',
        'attachment',
        'authority',
        'reference',
        'is_confirmed',
        'is_paid',
        'paid_at',
    ];

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class, 'gateway_id');
    }

    public function paytype(): BelongsTo
    {
        return $this->belongsTo(Paytype::class, 'paytype_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    protected function casts(): array
    {
        return [
            'is_confirmed' => 'boolean',
            'is_paid'      => 'boolean',
            'paid_at'      => 'datetime',
        ];
    }
}
