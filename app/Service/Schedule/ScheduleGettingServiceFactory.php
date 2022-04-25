<?php

namespace App\Service\Schedule;

use App\Service\Schedule\Exception\ScheduleGettingStrategyNotFoundException;

class ScheduleGettingServiceFactory
{
    /**
     * @throws ScheduleGettingStrategyNotFoundException
     */
    public function getByStrategy(string $strategy): ScheduleProcessingInterface
    {
        switch ($strategy) {
            case ScheduleByWeekStrategyService::STRATEGY_NAME:
                return app()->make(ScheduleByWeekStrategyService::class);
            default:
                throw new ScheduleGettingStrategyNotFoundException();
        }
    }
}
