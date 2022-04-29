<?php

namespace App\Service\Schedule\Exception;

use App\Exceptions\BaseException;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class CannotFindDayException extends BaseException
{
    public $message = 'Cannot find a day name for a given cell';

    public const CONTEXT_FIELD_CELL_COORDINATES = 'cellCoordinates';

    public function __construct($cellCoordinates = '')
    {
        parent::__construct();

        $this->context[self::CONTEXT_FIELD_CELL_COORDINATES] = $cellCoordinates;
    }
}
