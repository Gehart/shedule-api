<?php

declare(strict_types=1);

namespace App\Http\Request;

class ScheduleGettingRequest extends StandardRequest
{
    public function rules(): array
    {
        return [
            'group_id' => 'integer|required',
            'date' => 'date-format:Y-m-d|required',
        ];
    }
}
