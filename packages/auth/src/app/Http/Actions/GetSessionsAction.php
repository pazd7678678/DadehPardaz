<?php

namespace Pzamani\Auth\app\Http\Actions;

use App\Helpers\JwtHelper;
use Illuminate\Database\Eloquent\Collection;
use Pzamani\Auth\app\Models\Session;

class GetSessionsAction
{
    /**
     * @param string $token
     * @return Collection<Session>
     */
    public function run(string $token): Collection
    {
        $session = JwtHelper::getSession($token);
        return Session::query()->where('user_id', $session->user_id)->orderByDesc('login_at')->get();
    }
}
