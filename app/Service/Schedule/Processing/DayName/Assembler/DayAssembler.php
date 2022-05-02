<?php

namespace App\Service\Schedule\Processing\DayName\Assembler;

use App\Entities\Day;

class DayAssembler
{
    /**
     * @param $dayNumber
     * @return Day
     */
    public function create($dayNumber): Day
    {
        $dayKey = Day::DAY_KEY[$dayNumber];
        $dayName = Day::DAY_NAME[$dayNumber];
        return new Day($dayNumber, $dayName, $dayKey);
    }
}
