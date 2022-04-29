<?php

namespace App\Exceptions;

class BaseException extends \Exception implements ExceptionWithContextInterface
{
    protected array $context = [];

    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message ?: $this->message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
