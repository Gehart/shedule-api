<?php

namespace App\Service;

use App\Exceptions\Processing\NotFoundFileException;
use App\Infrastructure\FilesystemAdapter;
use App\Infrastructure\Spreadsheet\LoadingFileService;
use App\Service\Schedule\ScheduleByWeekStrategyService;
use App\Service\Schedule\ScheduleGettingRequest;
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
     * @param string $filepath
     * @param string|null $dateStart
     * @return void
     * @throws NotFoundFileException
     * @throws Schedule\Exception\ScheduleGettingStrategyNotFoundException
     * @throws \League\Flysystem\FilesystemException
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function getScheduleFromFile(string $filepath, ?string $dateStart): void
    {
        if ($this->filesystemAdapter->fileExists($filepath)) {
            throw new NotFoundFileException();
        }

        Log::notice('Loading a file', [
            'filepath' => $filepath,
        ]);
        $spreadsheet = $this->loadingFileService->load($filepath);

        $dateStart = $dateStart ? new \DateTime($dateStart) : $this->getClosestMonday();
        $dateEnd = $this->getScheduleDateEnd($dateStart);

        $scheduleGettingService = $this->scheduleGettingServiceFactory->getByStrategy(ScheduleByWeekStrategyService::STRATEGY_NAME);

        $scheduleGettingRequest = new ScheduleGettingRequest($spreadsheet, $dateStart, $dateEnd);

        $scheduleGettingService->getSchedule($scheduleGettingRequest);
    }

    /**
     * @return \DateTime
     */
    private function getClosestMonday(): \DateTime
    {
        $date = new \DateTime();
        $date->modify('next monday');
        $date->setTime(0, 0);
        return $date;
    }

    /**
     * @param \DateTime $dateStart
     * @return \DateTime
     * @throws \Exception
     */
    private function getScheduleDateEnd(\DateTime $dateStart): \DateTime
    {
        $dateEnd = new \DateTime($dateStart->format('Y-m-d'));
        $dateEnd->modify('+1 weeks');
        $dateEnd->modify('-1 minute');
        return $dateEnd;
    }
}
