<?php

namespace App\Service\Schedule\Exception;

use App\Exceptions\BaseException;

class CannotFindDayException extends BaseException
{
    public $message = 'Cannot find a day name for a given cell';
}
