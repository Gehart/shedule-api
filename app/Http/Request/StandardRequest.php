<?php

namespace App\Http\Request;

use Urameshibr\Requests\FormRequest;

class StandardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
