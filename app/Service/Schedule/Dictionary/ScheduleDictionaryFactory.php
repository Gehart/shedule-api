<?php

namespace App\Service\Schedule\Dictionary;

class ScheduleDictionaryFactory
{
    public function create(): ScheduleDictionary
    {
        $dictionaryData = config('dictionary', []);
        return new ScheduleDictionary($dictionaryData);
    }
}
