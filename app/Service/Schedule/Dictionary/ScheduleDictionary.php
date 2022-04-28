<?php

namespace App\Service\Schedule\Dictionary;

class ScheduleDictionary
{
    private array $dictionaryData;

    /**
     * @param array $dictionaryData
     */
    public function __construct(array $dictionaryData)
    {
        $this->dictionaryData = $dictionaryData;
    }

    public function getData(): array
    {
        return $this->dictionaryData;
    }
}
