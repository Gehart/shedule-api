<?php

declare(strict_types=1);

namespace App\Infrastructure\Ical;

use App\Domain\Entities\Lesson;
use App\Domain\Entities\Schedule;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\DateTime as IcalDateTime;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Domain\ValueObject\Timestamp;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;

class TranslatingToIcalFormatService implements TranslatingToIcalFormatInterface
{
    /**
     * @param Schedule $schedule
     * @return string
     */
    public function translateToIcalFormat(Schedule $schedule): string
    {
        $events = [];
        $lessons = $schedule->getLessons();
        /** @var Lesson $lesson */
        foreach ($lessons as $lesson) {
            $event = new Event();
            $dateCreated = $schedule->getCreated();
            $timestamp = new Timestamp($dateCreated);
            $event->touch($timestamp);

            $summary = $this->getCourseName($lesson);
            $event->setSummary($summary);

            $description = $this->getDescriptionForLesson($lesson);
            if ($description) {
                $event->setDescription($description);
            }

            $lessonStartDateTime = $schedule->getDayStart()->setTime(8, 0);
            $start = new IcalDateTime($lessonStartDateTime, false);
            $end = new IcalDateTime($lessonStartDateTime->modify('+90 minutes'), false);
            $occurrence = new TimeSpan($start, $end);
            $event->setOccurrence($occurrence);

            $events[] = $event;
            break;
        }


        $calendar = new Calendar($events);
//
        $componentFactory = new CalendarFactory();
        $calendarComponent = $componentFactory->createCalendar($calendar);
//

        return (string) $calendarComponent;
    }

    /**
     * @param Lesson $lesson
     * @return string
     */
    private function getCourseName(Lesson $lesson): string
    {
        $course = $lesson->getCourse();
        return $course->getCourseName() ?? $course->getRawCourse();
    }

    /**
     * @param Lesson $lesson
     * @return string|null
     */
    private function getDescriptionForLesson(Lesson $lesson): ?string
    {
        $classroom = $lesson->getClassroom() ?? null;
        return $classroom ? 'Аудитория ' . $lesson->getClassroom() : null;
    }
}
