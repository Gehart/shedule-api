<?php

declare(strict_types=1);

namespace App\Service\ConvertSchedule\Dto;

class IcalFileGeneratingResponse
{
    /**
     * @param string $content
     */
    public function __construct(
        private string $content
    ) {
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
