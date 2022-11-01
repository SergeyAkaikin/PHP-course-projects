<?php
declare(strict_types=1);

function readColorsFromFile(string $fileName): array
{
    $textFromFile = file_get_contents($fileName);
    $matchString = [];
    preg_match_all("/.*?\s#([A-F]|[0-9]){6}/u", $textFromFile, $matchString);
    $colorsMap = [];
    foreach ($matchString[0] as $colorString) {
        $colorString = trim($colorString);
        $keyValuePair = explode(' ', $colorString);
        if ($keyValuePair !== null && count($keyValuePair) == 2) {
            $colorsMap[$keyValuePair[1]] = $keyValuePair[0];
        }
    }

    return $colorsMap;
}

function replaceAndCollectColors(array $colorsMap, string $sourceFileName, string $targetFileName): array
{
    $textFromFile = file_get_contents($sourceFileName);
    $usedColorsMap = [];

    $hex3ToHex6 = function (string $colorString): string {
        $colorString = preg_replace_callback_array(
            ['/[a-fA-F0-9]/u' => fn(array $match): string => $match[0] . $match[0]],
            $colorString
        );
        return strtoupper($colorString);
    };

    $rgbToHex6 = function (string $colorString): string {
        $colorString = preg_replace('/(rgb|\(|\)|\s)/u', '', $colorString);
        [$redColor, $greenColor, $blueColor] = explode(',', $colorString);

        $redColor = dechex((int)$redColor);
        if (strlen($redColor) == 1) $redColor = '0' . $redColor;

        $greenColor = dechex((int)$greenColor);
        if (strlen($greenColor) == 1) $greenColor = '0' . $greenColor;

        $blueColor = dechex((int)$blueColor);
        if (strlen($blueColor) == 1) $blueColor = '0' . $blueColor;
        return strtoupper('#' . $redColor . $greenColor . $blueColor);
    };

    $hex6 = fn(string $hexFormat): string => strtoupper($hexFormat);

    $colorName = function (string $colorCode, callable $formatting) use (&$colorsMap, &$usedColorsMap) {
        $nonFormattedCode = $colorCode;
        $colorCode = $formatting($colorCode);

        if (key_exists($colorCode, $colorsMap)) {
            if (!key_exists($colorCode, $usedColorsMap)) $usedColorsMap[$colorCode] = $colorsMap[$colorCode];
            return $colorsMap[$colorCode];
        }

        return $nonFormattedCode;
    };
    file_put_contents(
        $targetFileName,
        preg_replace_callback_array(
            [
                '/#[a-fA-F0-9]{6}/u' => fn(array $match): string => $colorName($match[0], $hex6),
                '/#[a-fA-F0-9]{3}/u' => fn(array $match): string => $colorName($match[0], $hex3ToHex6),
                '/rgb\(\s*?\d{1,3},\s*?\d{1,3},\s*?\d{1,3}\s*?\)/u' => fn(array $match): string => $colorName($match[0], $rgbToHex6)
            ],
            $textFromFile
        )
    );

    $cmp = function ($first, $second): int { //comparison function for sort usedColors by keys
        $first = substr($first, 1);
        $second = substr($second, 1);
        $first = (int)hexdec($first);
        $second = (int)hexdec($second);
        return $first <=> $second;
    };

    uksort($usedColorsMap, $cmp);
    return $usedColorsMap;
}

