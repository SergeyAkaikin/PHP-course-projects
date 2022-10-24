<?php
declare(strict_types=1);

function readColorsFromFile(string $fileName): array
{
    $textFromFile = file_get_contents($fileName);
    $matchString = [];
    preg_match_all("/([A-Z]|[a-z])*? #([A-F]|[0-9]){6}/u", $textFromFile, $matchString);
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

function replaceAndCollectColors(array $colorsMap, string $pattern, string $sourceFileName, string $targetFileName): array
{

    $usedColorsMap = [];

    $textFromFile = file_get_contents($sourceFileName);

    $matchColors = [];
    preg_match_all($pattern, $textFromFile, $matchColors);

    $colorToChangeMap = []; //associative array with patterns(keys) for every found color to callables which return color value (values)
    foreach ($matchColors[0] as $currentColor) {
        $currentColor = strtoupper($currentColor);
        if (key_exists($currentColor, $colorsMap)) {
            if (!key_exists($currentColor, $usedColorsMap)) $usedColorsMap[$currentColor] = $colorsMap[$currentColor]; //add used color, if still hadn't him

            $currentColorPattern = '/' . $currentColor . '/u';
            if (!key_exists($currentColor, $colorToChangeMap))
                $colorToChangeMap[$currentColorPattern] = function ($match) use ($colorsMap) {
                    return $colorsMap[strtoupper($match[0])]; //add for current color function which returns color value
                };
        }
    }


    file_put_contents($targetFileName, preg_replace_callback_array($colorToChangeMap, $textFromFile));

    function cmp($first, $second): int
    { //comparison function for sort usedColors by keys
        $first = substr($first, 1);
        $second = substr($second, 1);
        $first = (int)hexdec($first);
        $second = (int)hexdec($second);
        return $first <=> $second;
    }

    uksort($usedColorsMap, 'cmp');
    return $usedColorsMap;
}

