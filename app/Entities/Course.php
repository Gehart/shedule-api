<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="course")
 */
class Course
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(name="raw_course", type="text")
     */
    private string $rawCourse;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $teacher;

    /**
     * @ORM\Column(name="course_name", type="text", nullable=true)
     */
    private ?string $courseName;

    /**
     * @ORM\OneToOne(targetEntity="Lesson", inversedBy="course")
     */
    private Lesson $lesson;

    /**
     * @param string $rawCourse
     * @param string|null $teacher
     * @param string|null $courseName
     */
    public function __construct(string $rawCourse, ?string $teacher = null, ?string $courseName = null)
    {
        $this->rawCourse = $rawCourse;
        $this->teacher = $teacher;
        $this->courseName = $courseName;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRawCourse(): string
    {
        return $this->rawCourse;
    }

    /**
     * @param string $rawCourse
     */
    public function setRawCourse(string $rawCourse): void
    {
        $this->rawCourse = $rawCourse;
    }

    /**
     * @return string|null
     */
    public function getTeacher(): ?string
    {
        return $this->teacher;
    }

    /**
     * @param string|null $teacher
     */
    public function setTeacher(?string $teacher): void
    {
        $this->teacher = $teacher;
    }

    /**
     * @return string|null
     */
    public function getCourseName(): ?string
    {
        return $this->courseName;
    }

    /**
     * @param string|null $courseName
     */
    public function setCourseName(?string $courseName): void
    {
        $this->courseName = $courseName;
    }
}
