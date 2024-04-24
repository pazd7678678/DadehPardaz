<?php

namespace Pzamani\Auth\app\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Pzamani\Auth\app\Models\Session;

/** @mixin Session */
class SessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'token'    => $this->token,
            'refresh'  => $this->refresh,
            'ip'       => $this->ip,
            'device'   => $this->device,
            'login_at' => $this->login_at->format('Y/m/d H:i:s'),
        ];
    }
}
