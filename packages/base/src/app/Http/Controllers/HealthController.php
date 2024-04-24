<?php

namespace Pzamani\Base\app\Http\Controllers;

use App\Http\Controllers\ApiController;

/**
 * @OA\Get(
 *   path="/api/base/health",
 *   tags={"Base"},
 *   summary="Health Check",
 *   operationId="base_health",
 *   security={{}},
 *
 *   @OA\Response(response=200, description="success")
 * )
 */
class HealthController extends ApiController
{
    public function __invoke()
    {
        return $this->success();
    }
}
