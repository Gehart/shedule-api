<?php

declare(strict_types=1);

namespace App\Http\Request;

class GroupGettingRequest extends StandardRequest
{
    public function rules(): array
    {
        return [
            'group_name' => 'string|required',
        ];
    }
}
