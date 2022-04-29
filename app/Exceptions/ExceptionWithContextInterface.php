<?php

namespace App\Exceptions;

interface ExceptionWithContextInterface
{
    /**
     * @return array
     */
    public function getContext(): array;
}
