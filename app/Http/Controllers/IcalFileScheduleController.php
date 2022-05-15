<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Entities\Lesson;
use App\Domain\Entities\LessonRepository;
use App\Domain\Entities\Schedule;
use App\Http\Assembler\IcalFileGenerationRequestAssembler;
use App\Http\Request\IcalFileGettingRequest;
use App\Http\Request\StandardRequest;
use App\Service\ConvertSchedule\IcalFileGeneratingService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class IcalFileScheduleController extends Controller
{
    /**
     * @param IcalFileGettingRequest $icalFileGettingRequest
     * @param IcalFileGenerationRequestAssembler $icalFileRequestAssembler
     * @param IcalFileGeneratingService $icalFileGeneratingService
     * @return SymfonyResponse
     */
    public function getIcalFileForSchedule(
        IcalFileGettingRequest             $icalFileGettingRequest,
        IcalFileGenerationRequestAssembler $icalFileRequestAssembler,
        IcalFileGeneratingService          $icalFileGeneratingService,
    ): SymfonyResponse
    {
        $request = $icalFileRequestAssembler->create($icalFileGettingRequest);
        $result = $icalFileGeneratingService->convertGroupScheduleToIcalFormat($request);

        return new SymfonyResponse($result->getContent(), SymfonyResponse::HTTP_OK, [
            'Content-type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="cal.ics"',
        ]);
    }

    /**
     * @param StandardRequest $request
     * @param EntityManagerInterface $entityManager
     * @param IcalFileGeneratingService $icalFileGeneratingService
     * @return SymfonyResponse
     * @throws \Exception
     */
    public function getIcalFileForTeacherSchedule(
        StandardRequest $request,
        EntityManagerInterface $entityManager,
        IcalFileGeneratingService $icalFileGeneratingService,
    ): SymfonyResponse
    {
        $teacherName = $request->input('teacher_name', 'дрозин');
        $date = new \DateTime($request->input('date', '2022-05-18'));

        /** @var LessonRepository $lessonRepository */
        $lessonRepository = $entityManager->getRepository(Lesson::class);
        $teacherLessons = $lessonRepository->getLessonsForTeacher($teacherName, $date);

        if (empty($teacherLessons)) {
            throw new RuntimeException('Can\'t find lessons for teacher');
        }

        $schedule = $teacherLessons[0]->getSchedule();
        $teacherSchedule = new Schedule($schedule->getDayStart(), $schedule->getDayEnd());
        $teacherSchedule->setLessons(new ArrayCollection($teacherLessons));

        $icalFileContent = $icalFileGeneratingService->convertScheduleToIcalFormat($teacherSchedule);

        return new SymfonyResponse($icalFileContent, SymfonyResponse::HTTP_OK, [
            'Content-type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="cal.ics"',
        ]);
    }
}
