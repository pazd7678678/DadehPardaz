<?php

namespace Pzamani\Auth\app\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class Session
 * @package Pzamani\Auth\app\Models
 *
 * @property string $id
 * @property string $user_id
 * @property string $token
 * @property string $refresh
 * @property string $ip
 * @property string $device
 * @property Carbon $login_at
 *
 * @property User $user
 */
class Session extends Model
{
    use HasUuids;

    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'sessions';
    protected $fillable = [
        'id',
        'user_id',
        'token',
        'refresh',
        'ip',
        'device',
        'login_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function casts(): array
    {
        return [
            'login_at' => 'datetime',
        ];
    }
}
