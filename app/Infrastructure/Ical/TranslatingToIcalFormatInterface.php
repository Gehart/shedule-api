<?php

namespace App\Infrastructure\Ical;

use App\Domain\Entities\Schedule;

interface TranslatingToIcalFormatInterface
{
    public function translateToIcalFormat(Schedule $schedule);
}
