<?php

namespace App\Domain\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="schedule")
 */
class Schedule
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @var Collection<Lesson>
     * @ORM\OneToMany(
     *     targetEntity="Lesson",
     *     mappedBy="schedule",
     *     cascade={"persist", "remove", "merge"},
     * )
     */
    private Collection $lessons;

    /**
     * @ORM\Column(name="day_start", type="datetime")
     */
    private \DateTime $dayStart;

    /**
     * @ORM\Column(name="day_end", type="datetime")
     */
    private \DateTime $dayEnd;

    /**
     * @ORM\OneToOne(
     *     targetEntity="Group",
     *     mappedBy="schedule",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private ?Group $group = null;

    /**
     * @param \DateTime $dayStart
     * @param \DateTime $dayEnd
     */
    public function __construct(\DateTime $dayStart, \DateTime $dayEnd)
    {
        $this->dayStart = $dayStart;
        $this->dayEnd = $dayEnd;
        $this->lessons = new ArrayCollection();
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
     * @return Collection|Lesson[]
     */
    public function getLessons(): Collection
    {
        return $this->lessons;
    }

    /**
     * @param Collection $lessons
     */
    public function setLessons(Collection $lessons): void
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

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
