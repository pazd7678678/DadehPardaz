<?php

namespace Pzamani\Auth\app\Http\Controllers;

use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\JsonResponse;
use Pzamani\Auth\app\Http\Actions\GetSessionsAction;
use Pzamani\Auth\app\Http\Requests\GetSessionsRequest;
use Pzamani\Auth\app\Http\Resources\SessionResource;

class GetSessionsController extends ApiController
{
    /**
     * @OA\Get(
     *   path="/api/auth/sessions",
     *   tags={"Auth"},
     *   summary="Get all of user sessions (first item is current)",
     *   operationId="auth_get_sessions",
     *   security={{"auth":{}}},
     *
     *   @OA\Response(response=200, description="Success"),
     * )
     **/
    public function __invoke(GetSessionsRequest $request): JsonResponse
    {
        try {
            return $this->success($this->transform(app(GetSessionsAction::class)->run($request->bearerToken()), SessionResource::class));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), [], $e->getCode());
        }
    }
}
