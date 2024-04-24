<?php

namespace Pzamani\Auth\app\Http\Controllers;

use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\JsonResponse;
use Pzamani\Auth\app\Http\Actions\CreateSessionForUserAction;
use Pzamani\Auth\app\Http\Actions\FindUserByMobileAndPasswordAction;
use Pzamani\Auth\app\Http\Requests\LoginRequest;
use Pzamani\Auth\app\Http\Resources\SessionResource;

/**
 * @OA\Post(
 *   path="/api/auth/login",
 *   tags={"Auth"},
 *   summary="Login",
 *   operationId="auth_login",
 *   security={{}},
 *
 *   @OA\RequestBody(required=true,
 *     @OA\MediaType(mediaType="application/x-www-form-urlencoded",
 *       @OA\Schema(required={"mobile","password"},
 *         @OA\Property(property="mobile",type="string",example="09123456789"),
 *         @OA\Property(property="password",type="string",example="123456"),
 *       )
 *     )
 *   ),
 *
 *   @OA\Response(response=200, description="success")
 * )
 */
class LoginController extends ApiController
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        try {
            $data = [
                'mobile'   => $request->validated('mobile'),
                'password' => $request->validated('password'),
            ];
            $user = app(FindUserByMobileAndPasswordAction::class)->run($data['mobile'], $data['password']);
            $session = app(CreateSessionForUserAction::class)->run($user);
            return $this->success($this->transform($session, SessionResource::class));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), status: $e->getCode());
        }
    }
}
