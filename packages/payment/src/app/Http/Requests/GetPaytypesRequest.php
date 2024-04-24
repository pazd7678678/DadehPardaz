<?php

namespace Pzamani\Payment\app\Http\Requests;

use App\Http\Requests\Request;

class GetPaytypesRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required|string|exists:sessions,token',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'token' => $this->bearerToken(),
        ]);
    }
}
