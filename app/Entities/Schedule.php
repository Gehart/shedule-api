<?php

namespace App\Entities;

class Schedule
{
    private \DateTime $dayStart;
    private \DateTime $dayEnd;

    // group
    /**
     * @var array<Lesson>
     */
    private array $lessons;

    private ?Group $group = null;

    /**
     * @param array $lessons
     * @param \DateTime $dayStart
     * @param \DateTime $dayEnd
     */
    public function __construct(array $lessons, \DateTime $dayStart, \DateTime $dayEnd)
    {
        $this->lessons = $lessons;
        $this->dayStart = $dayStart;
        $this->dayEnd = $dayEnd;
    }

    /**
     * @return \DateTime
     */
    public function getDayStart(): \DateTime
    {
        return $this->dayStart;
    }

    /**
     * @param \DateTime $dayStart
     */
    public function setDayStart(\DateTime $dayStart): void
    {
        $this->dayStart = $dayStart;
    }

    /**
     * @return \DateTime
     */
    public function getDayEnd(): \DateTime
    {
        return $this->dayEnd;
    }

    /**
     * @param \DateTime $dayEnd
     */
    public function setDayEnd(\DateTime $dayEnd): void
    {
        $this->dayEnd = $dayEnd;
    }

    /**
     * @return Lesson[]
     */
    public function getLessons(): array
    {
        return $this->lessons;
    }

    /**
     * @param Lesson[] $lessons
     */
    public function setLessons(array $lessons): void
    {
        $this->lessons = $lessons;
    }

    /**
     * @return Group|null
     */
    public function getGroup(): ?Group
    {
        return $this->group;
    }

    /**
     * @param Group|null $group
     */
    public function setGroup(?Group $group): void
    {
        $this->group = $group;
    }
}
