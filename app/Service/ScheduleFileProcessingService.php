<?php

namespace App\Service;

use App\Exceptions\Processing\NotFoundFileException;
use App\Infrastructure\FilesystemAdapter;
use App\Service\Schedule\ScheduleByWeekStrategyService;
use App\Service\Schedule\ScheduleGettingServiceFactory;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ScheduleFileProcessingService
{
    public function __construct(
        private FilesystemAdapter $filesystemAdapter,
        private ScheduleGettingServiceFactory $scheduleGettingServiceFactory,
    ) {
    }

    /**
     * @param $filepath
     * @return void
     * @throws \Throwable
     */
    public function getScheduleFromFile($filepath): void
    {
        if ($this->filesystemAdapter->fileExists($filepath)) {
            throw new NotFoundFileException();
        }

        Log::notice('Start to load a file');
        $reader = IOFactory::createReaderForFile($filepath);
        $spreadsheet = $reader->load($filepath);

        $scheduleGettingService = $this->scheduleGettingServiceFactory->getByStrategy(ScheduleByWeekStrategyService::STRATEGY_NAME);
        $scheduleGettingService->getSchedule($spreadsheet);



    }
}
