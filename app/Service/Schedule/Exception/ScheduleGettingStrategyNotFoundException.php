<?php

namespace App\Service\Schedule\Exception;

use App\Exceptions\BaseException;

class ScheduleGettingStrategyNotFoundException extends BaseException
{
    public $message = 'Could not get the schedule getting strategy by strategy name';
}
