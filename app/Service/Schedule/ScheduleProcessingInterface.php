<?php

namespace App\Service\Schedule;

interface ScheduleProcessingInterface
{
    public const STRATEGY_NAME = 'default';

    public function getSchedule(ScheduleGettingRequest $scheduleGettingRequest);
}
