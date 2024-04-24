<?php

namespace Pzamani\Auth\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RefreshRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required|string|exists:sessions,refresh',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'token' => $this->bearerToken(),
        ]);
    }
}
