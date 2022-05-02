<?php

namespace App\Entities;

class Day
{
    private int $number;
    private string $name;
    private string $key;

    public const
        MONDAY = 1,
        TUESDAY = 2,
        WEDNESDAY = 3,
        THURSDAY = 4,
        FRIDAY = 5,
        SATURDAY = 6;

    public const DAY_KEY = [
        self::MONDAY => 'monday',
        self::TUESDAY => 'tuesday',
        self::WEDNESDAY => 'wednesday',
        self::THURSDAY => 'thursday',
        self::FRIDAY => 'friday',
        self::SATURDAY => 'saturday',
    ];

    public const DAY_NAME = [
        self::MONDAY => 'Понедельник',
        self::TUESDAY => 'Вторник',
        self::WEDNESDAY => 'Среда',
        self::THURSDAY => 'Четверг',
        self::FRIDAY => 'Пятница',
        self::SATURDAY => 'Суббота',
    ];

    /**
     * @param int $number
     * @param string $name
     * @param string $key
     */
    public function __construct(int $number, string $name, string $key)
    {
        $this->number = $number;
        $this->name = $name;
        $this->key = $key;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }
}
