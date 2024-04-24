<?php

namespace Pzamani\Payment\app\Http\Requests;

use App\Http\Requests\Request;

class PayRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token'        => 'required|string|exists:sessions,token',
            'paytype_id'   => 'required|integer|exists:paytypes,id',
            'description'  => 'required|string',
            'amount'       => 'required|integer|gt:0',
            'nationalcode' => 'required|string|size:10,exists:users,nationalcode',
            'iban'         => 'required|string|size:26|regex:#^[A-Z]{2}[0-9]{24}$#',
            'attachment'   => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'token'        => $this->bearerToken(),
            'paytype_id'   => $this->post('paytype_id'),
            'description'  => $this->post('description'),
            'amount'       => $this->post('amount'),
            'nationalcode' => $this->post('nationalcode'),
            'iban'         => $this->post('iban'),
            'attachment'   => $this->file('attachment'),
        ]);
    }
}
