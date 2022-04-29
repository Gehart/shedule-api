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

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->dictionaryData;
    }

    /**
     * @return array
     */
    public function getDayNameData(): array
    {
        return $this->dictionaryData['dayNames'] ?: [];
    }
}
