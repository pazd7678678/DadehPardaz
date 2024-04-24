<?php

namespace Pzamani\Payment\app\Http\Controllers;

use App\Helpers\JwtHelper;
use App\Http\Controllers\ApiController;
use Exception;
use Pzamani\Payment\app\Http\Actions\PayAction;
use Pzamani\Payment\app\Http\Requests\PayRequest;
use Pzamani\Payment\app\Http\Resources\PaymentResource;

/**
 * @OA\Post(
 *   path="/api/payment/pay",
 *   tags={"Payment"},
 *   summary="Request a new payment",
 *   operationId="post_payment_pay",
 *   security={{"auth":{}}},
 *
 *   @OA\RequestBody(required=true,
 *     @OA\MediaType(mediaType="multipart/form-data",
 *       @OA\Schema(required={"paytype_id","description","amount","nationalcode","iban"},
 *         @OA\Property(property="paytype_id",type="integer",example=1),
 *         @OA\Property(property="description",type="string",example="Sample Payment"),
 *         @OA\Property(property="amount",type="integer",example=1000000),
 *         @OA\Property(property="nationalcode",type="string",example="1234567890"),
 *         @OA\Property(property="iban",type="string",example="IR123456789012345678901234"),
 *         @OA\Property(property="attachment",type="file"),
 *       )
 *     )
 *   ),
 *
 *   @OA\Response(response=200, description="success")
 * )
 */
class PayController extends ApiController
{
    public function __invoke(PayRequest $request)
    {
//        try {
            $data = $this->sanitize($request, [
                'paytype_id'   => $request->validated('paytype_id'),
                'sender_id'    => JwtHelper::getUser($request->validated('token'))->id,
                'description'  => $request->validated('description'),
                'amount'       => $request->validated('amount'),
                'nationalcode' => $request->validated('nationalcode'),
                'iban'         => $request->validated('iban'),
                'attachment'   => $request->validated('attachment'),
            ]);
            return $this->success($this->transform(app(PayAction::class)->run($data['paytype_id'], $data['sender_id'], $data['nationalcode'], $data['description'], $data['amount'], $data['iban'], $data['attachment'] ?? null), PaymentResource::class));
//        } catch (Exception $e) {
//            return $this->error($e->getFile(), status: $e->getCode());
//        }
    }
}
