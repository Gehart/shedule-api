<?php

declare(strict_types=1);

namespace App\Service\ConvertSchedule;

use App\Domain\Entities\Schedule;
use App\Domain\Entities\ScheduleRepository;
use App\Infrastructure\Ical\TranslatingToIcalFormatInterface;
use App\Service\ConvertSchedule\Dto\IcalFileGeneratingRequest;
use App\Service\ConvertSchedule\Dto\IcalFileGeneratingResponse;
use Doctrine\ORM\EntityManagerInterface;

class IcalFileGeneratingService
{
    public function __construct(
        private TranslatingToIcalFormatInterface $translatingToIcalFormatService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param IcalFileGeneratingRequest $request
     * @return IcalFileGeneratingResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function convertGroupScheduleToIcalFormat(IcalFileGeneratingRequest $request): IcalFileGeneratingResponse
    {
        $group = $request->getGroup();
        $date = $request->getDate();
        /** @var ScheduleRepository $scheduleRepository */
        $scheduleRepository = $this->entityManager->getRepository(Schedule::class);
        $schedule = $scheduleRepository->getScheduleForGroup($group, $date);
        $icalFileContent = $this->convertScheduleToIcalFormat($schedule);

        return new IcalFileGeneratingResponse($icalFileContent);
    }

    /**
     * @param Schedule $schedule
     * @return string
     */
    public function convertScheduleToIcalFormat(Schedule $schedule): string
    {
        return $this->translatingToIcalFormatService->translateToIcalFormat($schedule);
    }
}
