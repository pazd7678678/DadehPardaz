<?php

namespace Pzamani\Auth\app\Http\Controllers;

use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\JsonResponse;
use Pzamani\Auth\app\Http\Actions\RefreshAction;
use Pzamani\Auth\app\Http\Requests\RefreshRequest;
use Pzamani\Auth\app\Http\Resources\SessionResource;

class RefreshController extends ApiController
{
    /**
     * @OA\Patch(
     *   path="/api/auth/refresh",
     *   tags={"Auth"},
     *   summary="Refresh token via refresh_token set as bearer authorization header",
     *   operationId="auth_refresh",
     *   security={{"auth":{}}},
     *
     *   @OA\Response(response=200, description="success")
     * )
     **/
    public function __invoke(RefreshRequest $request): JsonResponse
    {
        try {
            $data = $this->sanitize($request, [
                'token' => $request->bearerToken(),
            ]);
            $session = app(RefreshAction::class)->run($data['token']);
            return $this->success($this->transform($session, SessionResource::class));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), [], $e->getCode());
        }
    }
}
