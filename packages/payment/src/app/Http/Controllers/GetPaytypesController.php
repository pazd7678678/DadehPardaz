<?php

namespace Pzamani\Payment\app\Http\Controllers;

use App\Http\Controllers\ApiController;
use Pzamani\Payment\app\Http\Actions\GetPaytypesAction;
use Pzamani\Payment\app\Http\Requests\GetPaytypesRequest;
use Pzamani\Payment\app\Http\Resources\PaytypeResource;

/**
 * @OA\Get(
 *   path="/api/payment/paytypes",
 *   tags={"Payment"},
 *   summary="Get paytypes",
 *   operationId="get_payment_paytypes",
 *   security={{"auth":{}}},
 *
 *   @OA\Response(response=200, description="success")
 * )
 */
class GetPaytypesController extends ApiController
{
    public function __invoke(GetPaytypesRequest $request)
    {
        return $this->success($this->transform(app(GetPaytypesAction::class)->run(), PaytypeResource::class));
    }
}
