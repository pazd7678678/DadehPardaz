<?php

namespace App\Http\Middlewares;

use App\Helpers\JwtHelper;
use App\Http\Controllers\ApiController;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthCheckMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        try {
            $token = $request->bearerToken();
            if (trim($token) === '') {
                throw new Exception('JWT is required', Response::HTTP_FORBIDDEN);
            }
            if (!(JwtHelper::parseToken($token) && JwtHelper::getSession($token)) && !JwtHelper::getUser($token)) {
                throw new Exception('Invalid token', Response::HTTP_FORBIDDEN);
            }
            if (!JwtHelper::checkToken($token)) {
                throw new Exception('Token is expired', Response::HTTP_UNAUTHORIZED);
            }
        } catch (Exception $e) {
            $result = null;
            $meta = null;
            $message = $e->getMessage();
            $errors = null;
            $errorCode = $e->getCode();
            return response()->json(compact('result', 'meta', 'message', 'errors'), in_array($errorCode, ApiController::$statuses) ? $errorCode : Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $next($request);
    }
}
