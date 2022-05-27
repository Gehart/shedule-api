<?php

declare(strict_types=1);

namespace App\Http\Formatter;

use App\Domain\Entities\Course;
use App\Domain\Entities\Day;
use App\Domain\Entities\Lesson;
use App\Domain\Entities\Schedule;

class GettingScheduleResponseFormatter
{
    public function format(Schedule $schedule): array
    {
        $lessons = $schedule->getLessons()->toArray();
        $lessonsByDays = $this->groupByDays($lessons);

        $formattedSchedule = [];
        foreach ($lessonsByDays as $dayKey => $days) {
            foreach ($days as $lesson) {
                $formattedSchedule[$dayKey][] = $this->formatLesson($lesson);
            }
        }

        $group = $schedule->getGroup();

        return [
            'schedule' => $formattedSchedule,
            'group' => [
                'group_id' => $group->getId(),
                'group_name' => $group->getName(),
            ]
        ];
    }

    /**
     * @param array<Lesson> $lessons
     * @return array<string, array<Lesson>>
     */
    private function groupByDays(array $lessons): array
    {
        $lessonsByDay = [];
        foreach ($lessons as $lesson) {
            $dayKey = Day::DAY_KEY[$lesson->getDayNumber()];
            $lessonsByDay[$dayKey][] = $lesson;
        }
        return $lessonsByDay;
    }

    /**
     * @param Lesson $lesson
     * @return array
     */
    public function formatLesson(Lesson $lesson): array
    {
        $dayName = $lesson->getDayNumber() ? Day::DAY_NAME[$lesson->getDayNumber()] : null;
        $course = $this->formatCourse($lesson->getCourse());
        return [
            'sequence_number' => $lesson->getSequenceNumber(),
            'start_time' => $lesson->getStartTime(),
            'type_of_lesson' => $lesson->getTypeOfLesson(),
            'classroom' => $lesson->getClassroom(),
            'day' => $dayName,
            'course' => $course,
        ];
    }

    /**
     * @param Course $course
     * @return array<string, mixed>
     */
    public function formatCourse(Course $course): array
    {
        return [
            'raw_value' => $course->getRawCourse(),
            'course_name' => $course->getCourseName(),
            'teacher' => $course->getTeacher(),
        ];
    }
}
