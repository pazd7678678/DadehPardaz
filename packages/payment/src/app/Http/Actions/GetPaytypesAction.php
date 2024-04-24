<?php

namespace Pzamani\Payment\app\Http\Actions;

use Illuminate\Database\Eloquent\Collection;
use Pzamani\Payment\app\Models\Paytype;

class GetPaytypesAction
{
    /**
     * @return Collection<Paytype>
     */
    public function run(): Collection
    {
        return Paytype::query()->where('is_active', 1)->get();
    }
}
