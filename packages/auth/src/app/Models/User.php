<?php

namespace Pzamani\Auth\app\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Support\Carbon;
use Pzamani\Payment\app\Models\Payment;

/**
 * Class User
 * @package Pzamani\Auth\app\Models
 *
 * @property string $id
 * @property string $mobile
 * @property string $password
 * @property string $email
 * @property string $name
 * @property string $family
 * @property bool $is_active
 * @property Carbon $registered_at
 *
 * @property Collection<Payment> $receiverPayments
 * @property Collection<Payment> $senderPayments
 * @property Collection<Session> $sessions
 */
class User extends AuthUser implements FilamentUser, HasName
{
    use HasUuids;

    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'users';
    protected $fillable = [
        'id',
        'mobile',
        'password',
        'email',
        'name',
        'family',
        'is_active',
        'registered_at',
    ];

    public function receiverPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'receiver_id');
    }

    public function senderPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'sender_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class, 'user_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->id == '00000000-0000-0000-0000-000000000000';
    }

    public function getFilamentName(): string
    {
        return $this->name . ' ' . $this->family;
    }

    protected function casts(): array
    {
        return [
            'is_active'     => 'boolean',
            'registered_at' => 'datetime',
        ];
    }
}
