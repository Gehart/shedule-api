<?php

declare(strict_types=1);

namespace App\Domain\Entities\TeacherSchedule;

use App\Domain\Entities\Schedule;
use Doctrine\ORM\Mapping as ORM;

class TeacherSchedule
{
    private string $teacher;
    private int $schedule_id;
}
