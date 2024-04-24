<?php

namespace Pzamani\Auth\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile'   => 'required|string|regex:#^0?9[0-39][0-9]{8}$#',
            'password' => 'required|string',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'mobile'   => $this->post('mobile'),
            'password' => $this->post('password'),
        ]);
    }
}
