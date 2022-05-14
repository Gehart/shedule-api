<?php

namespace App\Infrastructure\Ical;

use App\Domain\Entities\Schedule;

interface ConvertToIcalFormatInterface
{
    public function translateToIcalFormat(Schedule $schedule);
}
