<?php

namespace Pzamani\Payment\app\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Paytype
 * @package Pzamani\Payment\app\Models
 *
 * @property int $id
 * @property string $name
 * @property bool $is_active
 *
 * @property Collection<Payment> $payments
 */
class Paytype extends Model
{
    public $timestamps = false;
    protected $table = 'paytypes';
    protected $fillable = [
        'id',
        'name',
        'is_active',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'paytype_id');
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
