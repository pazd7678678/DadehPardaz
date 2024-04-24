<?php

namespace Pzamani\Auth\app\Http\Actions;

use App\Helpers\JwtHelper;
use Exception;
use Illuminate\Http\Request;
use Pzamani\Auth\app\Models\Session;

class RefreshAction
{
    /**
     * @throws Exception
     */
    public function run(string $refreshToken): ?Session
    {
        $session = JwtHelper::getSession($refreshToken);
        $token = JwtHelper::createToken($session->id, $session->user_id, 86400)->toString();
        $refresh = JwtHelper::createToken($session->id, $session->user_id, 30 * 86400)->toString();
        $request = Request::capture();
        $session->update([
            'token'   => $token,
            'refresh' => $refresh,
            'ip'      => $request->ip(),
            'device'  => $request->userAgent(),
        ]);
        return $session->refresh();
    }
}
