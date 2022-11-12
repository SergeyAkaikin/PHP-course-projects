<?php

declare(strict_types=1);

namespace App\DataAccess;

use Generator;

class ColorStorage
{
    /**
     * @param Generator|null $colorReader
     * @param Generator|null $colorRecorder
     */
    public function __construct(
        private readonly ?Generator $colorReader,
        private readonly ?Generator $colorRecorder
    )
    {
    }

    /**
     * @return array<string, string>
     */
    public function readColorsMap(): array
    {
        $colorsMap = [];
        foreach ($this->colorReader as $colorCode => $colorValue) {
            $colorsMap[$colorCode] = $colorValue;
        }

        return $colorsMap;
    }

    /**
     * @param array $colorsMap
     * @return void
     */
    public function writeColorsMap(array $colorsMap): void
    {
        foreach ($colorsMap as $colorCode => $colorValue) {
            $this->colorRecorder->send("{$colorCode} {$colorValue}\n");
        }
    }
}
