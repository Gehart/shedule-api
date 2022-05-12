<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class EntityWasNotFoundException extends \Exception
{
    protected $message = 'Entity was not found by params!';
}
