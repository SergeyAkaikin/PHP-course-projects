<?php

declare(strict_types=1);

namespace App\DataAccess;

class TextStorage
{
    /**
     * @param Generator|null $textReader
     * @param Generator|null $textWriter
     */
    public function __construct(
        private readonly ?Generator $textReader,
        private readonly ?Generator $textWriter
    )
    {
    }

    /**
     * @return array<string>
     */
    public function readText(): array
    {
        $textArray = [];
        foreach ($this->textReader as $line) {
            $textArray[] = $line;
        }
        return $textArray;
    }

    /**
     * @param array $text
     * @return void
     */
    public function writeText(array $text): void
    {
        foreach ($text as $line) {
            $this->textWriter->send($line);
        }
    }
}