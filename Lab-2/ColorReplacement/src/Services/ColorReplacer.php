<?php

declare(strict_types=1);

namespace App\Services;

class ColorReplacer
{

    /**
     * @param string $text
     * @param array $colorsMap
     * @param array $usedColorsMap
     * @return string
     */
    public function replaceColors(string $text, array &$colorsMap, array &$usedColorsMap): string
    {

        $colorName = function (string $colorCode, callable $formatting) use (&$colorsMap, &$usedColorsMap): string {
            $nonFormattedCode = $colorCode;
            $colorCode = $formatting($colorCode);

            if (key_exists($colorCode, $colorsMap)) {
                if (!key_exists($colorCode, $usedColorsMap)) {
                    $usedColorsMap[$colorCode] = $colorsMap[$colorCode];
                }
                return $colorsMap[$colorCode];
            }

            return $nonFormattedCode;
        };

        $text = preg_replace_callback_array(
            [
                '/#\b[a-fA-F0-9]{6}\b/u' => fn(array $match): string => $colorName($match[0], fn(string $hexFormat): string => strtoupper($hexFormat)),
                '/#\b[a-fA-F0-9]{3}\b/u' => fn(array $match): string => $colorName($match[0], '\App\Utils\ColorUtils::hex3ToHex6'),
                '/rgb\(\s*?\d{1,3},\s*?\d{1,3},\s*?\d{1,3}\s*?\)/u' => fn(array $match): string => $colorName($match[0], '\App\Utils\ColorUtils::rgbToHex6')
            ],
            $text
        );
        uksort($usedColorsMap, fn(string $first, string $second): int => $first <=> $second);
        return $text;
    }
}