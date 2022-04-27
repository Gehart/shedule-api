<?php

namespace App\Service;

use App\Exceptions\Processing\NotFoundFileException;
use App\Infrastructure\FilesystemAdapter;
use App\Infrastructure\Spreadsheet\LoadingFileService;
use App\Service\Schedule\ScheduleByWeekStrategyService;
use App\Service\Schedule\ScheduleGettingServiceFactory;
use Illuminate\Support\Facades\Log;

class ScheduleFileProcessingService
{
    public function __construct(
        private FilesystemAdapter $filesystemAdapter,
        private ScheduleGettingServiceFactory $scheduleGettingServiceFactory,
        private LoadingFileService $loadingFileService,
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

        Log::notice('Loading a file', [
            'filepath' => $filepath,
        ]);
        $spreadsheet = $this->loadingFileService->load($filepath);

        $scheduleGettingService = $this->scheduleGettingServiceFactory->getByStrategy(ScheduleByWeekStrategyService::STRATEGY_NAME);
        $scheduleGettingService->getSchedule($spreadsheet);
    }
}
