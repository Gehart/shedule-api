<?php

namespace App\Service\Schedule\Exception;

use App\Exceptions\BaseException;

class CannotFindFirstGroupNameException extends BaseException
{
    public $message = 'Could not find the first group name on given list';
}
