<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Mofid\Base\app\Models\Language;

abstract class Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
