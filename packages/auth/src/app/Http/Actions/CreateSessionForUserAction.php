<?php

namespace Pzamani\Auth\app\Http\Actions;

use App\Helpers\JwtHelper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Pzamani\Auth\app\Models\Session;
use Pzamani\Auth\app\Models\User;
use Symfony\Component\HttpFoundation\Response;

class CreateSessionForUserAction
{
    /**
     * @throws Exception
     */
    public static function run(User $user): Session
    {
        $session = null;
        DB::transaction(function () use ($user, &$session) {
            $request = Request::capture();
            $sessionId = Str::uuid()->toString();
            $token = JwtHelper::createToken($sessionId, $user->id, 86400)->toString();
            $refresh = JwtHelper::createToken($sessionId, $user->id, 30 * 86400)->toString();
            $session = Session::query()->create([
                'id'       => $sessionId,
                'user_id'  => $user->id,
                'token'    => $token,
                'refresh'  => $refresh,
                'ip'       => $request->ip(),
                'device'   => $request->userAgent(),
                'login_at' => now(),
            ]);
        });
        if (is_null($session)) {
            throw new Exception('Cannot Create Session', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $session;
    }
}
