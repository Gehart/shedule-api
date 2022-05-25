<?php

declare(strict_types=1);

namespace App\Infrastructure\Ical;

use App\Domain\Entities\Lesson;
use App\Domain\Entities\Schedule;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\Date;
use Eluceo\iCal\Domain\ValueObject\DateTime as IcalDateTime;
use Eluceo\iCal\Domain\ValueObject\Occurrence;
use Eluceo\iCal\Domain\ValueObject\SingleDay;
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

            $summary = $this->getSummary($lesson);
            $event->setSummary($summary);

            $description = $this->getDescriptionForLesson($lesson);
            $event->setDescription($description);

            $occurrence = $this->getOccurrence($lesson, $schedule);
            $event->setOccurrence($occurrence);

            $events[] = $event;
        }


        $calendar = new Calendar($events);

        $componentFactory = new CalendarFactory();
        $calendarComponent = $componentFactory->createCalendar($calendar);

        return (string) $calendarComponent;
    }

    /**
     * @param Lesson $lesson
     * @return string
     */
    private function getCourseName(Lesson $lesson): string
    {
        $course = $lesson->getCourse();
        $courseName = $course->getCourseName() ?? $course->getRawCourse();
        return '"' . $courseName . '"';
    }

    /**
     * @param Lesson $lesson
     * @return string|null
     */
    private function getDescriptionForLesson(Lesson $lesson): ?string
    {
        $description = [];

        if ($lesson->getCourse()->getTeacher()) {
            $description[] = 'Преподователь: ' . $lesson->getCourse()->getTeacher();
        }

        $group = $lesson->getSchedule()->getGroup();
        if ($group) {
            $description[] = 'Группа: ' . $group->getName();
        }

        if ($lesson->getClassroom()) {
            $description[] = 'Аудитория: ' . $lesson->getClassroom();
        }

        if ($lesson->getTypeOfLesson()) {
            $description[] = 'Тип урока: ' . $lesson->getTypeOfLesson();
        }

        if ($lesson->getSequenceNumber()) {
            $description[] = $lesson->getSequenceNumber();
        }

        return implode(PHP_EOL, $description);
    }

    /**
     * @param Lesson $lesson
     * @return string
     */
    private function getSummary(Lesson $lesson): string
    {
        $courseName = $this->getCourseName($lesson);
        return $courseName;
    }

    private function getOccurrence(Lesson $lesson, Schedule $schedule): Occurrence
    {
        $occurrence = null;
        $lessonStartTime = $lesson->getStartTime();
        $validStartTime = $this->validateTime($lessonStartTime);

        if ($validStartTime) {
            $datetime = $this->createStartDateTimeForLesson($lesson, $schedule);
            if ($datetime) {
                $start = new IcalDateTime($datetime, false);
                $end = new IcalDateTime($datetime->modify('+90 minutes'), false);
                $occurrence = new TimeSpan($start, $end);
            }
        } else {
            $lessonDayNumber = $lesson->getDayNumber();
            $lessonDayStart = \DateTimeImmutable::createFromMutable($schedule->getDayStart());
            $lessonDay = $lessonDayStart->modify('+' . $lessonDayNumber - 1 . ' day');

            if (!$lessonDay) {
                $lessonDay = $schedule->getDayStart();
            }

            $icalDate = new Date($lessonDay);
            $occurrence = new SingleDay($icalDate);
        }

        return $occurrence;
    }

    /**
     * @param string|null $time
     * @return bool
     */
    private function validateTime(?string $time): bool
    {
        if (!$time) {
            return false;
        }

        $dateObj = \DateTimeImmutable::createFromFormat('d.m.Y H:i', "10.10.2010 " . $time);
        return $dateObj !== false;
    }

    /**
     * @param Lesson $lesson
     * @param Schedule $schedule
     * @return \DateTimeInterface|null
     */
    private function createStartDateTimeForLesson(Lesson $lesson, Schedule $schedule): \DateTimeInterface|null
    {
        $lessonStartTime = $lesson->getStartTime();
        $lessonDayNumber = $lesson->getDayNumber();

        $scheduleDayStart = \DateTimeImmutable::createFromMutable($schedule->getDayStart());
        $lessonDay = $scheduleDayStart->modify('+' . $lessonDayNumber - 1 . ' day');
        $lessonDayStart = $lessonDay->format('Y-m-d');

        $lessonStartDateTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i', $lessonDayStart . ' ' . $lessonStartTime);

        return $lessonStartDateTime ?? null;
    }
}
