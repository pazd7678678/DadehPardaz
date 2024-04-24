<?php

namespace Pzamani\Auth\app\Http\Controllers;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Pzamani\Auth\app\Http\Requests\CheckRequest;

class CheckController extends ApiController
{
    /**
     * @OA\Get(
     *   path="/api/auth/check",
     *   tags={"Auth"},
     *   summary="Check Token Validity",
     *   operationId="auth_check",
     *   security={{"auth":{}}},
     *
     *   @OA\Response(response=200, description="Success"),
     * )
     **/
    public function __invoke(CheckRequest $request): JsonResponse
    {
        return $this->success();
    }
}
