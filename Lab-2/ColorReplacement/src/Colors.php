<?php
declare(strict_types=1);

/**
 * @param string $fileName
 * @return string
 */

function getFileText(string $fileName): string
{
    $file = fopen($fileName, 'r');
    $text = '';
    while (($line = fgets($file)) !== false) {
        $text .= $line;
    }
    fclose($file);

    return $text;
}

/**
 * @param string $fileName
 * @return array<string, string>
 */

function readColorsFromFile(string $fileName): array
{
    $textFromFile = getFileText($fileName);
    $matchString = [];
    preg_match_all("/\b.*?\s#([A-F]|[0-9]){6}\b/u", $textFromFile, $matchString);
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

/**
 * @param string $colorString
 * @return string
 */

function hex3ToHex6(string $colorString): string
{
    $colorStringFormatted = '#' . $colorString[1] . $colorString[1];
    $colorStringFormatted .= $colorString[2] . $colorString[2];
    $colorStringFormatted .= $colorString[3] . $colorString[3];

    return strtoupper($colorStringFormatted);
}

/**
 * @param string $colorString
 * @return string
 */

function rgbToHex6(string $colorString): string
{
    $colorString = preg_replace('/(rgb|\(|\)|\s)/u', '', $colorString);
    [$redColor, $greenColor, $blueColor] = explode(',', $colorString);

    $redColor = dechex((int)$redColor);
    if (strlen($redColor) === 1) {
        $redColor = '0' . $redColor;
    }

    $greenColor = dechex((int)$greenColor);
    if (strlen($greenColor) === 1) {
        $greenColor = '0' . $greenColor;
    }

    $blueColor = dechex((int)$blueColor);
    if (strlen($blueColor) === 1) {
        $blueColor = '0' . $blueColor;
    }
    return strtoupper('#' . $redColor . $greenColor . $blueColor);
}

/**
 * @param array $colorsMap
 * @param string $sourceFileName
 * @return array<string, string>
 */
function getUsedColors(array $colorsMap, string $sourceFileName): array
{
    $sourceText = getFileText($sourceFileName);

    $usedColorsMap = [];

    $usedColorsWithFormat = function (
        string   $regexForColors,
        callable $formatting
    ) use (&$colorsMap, &$usedColorsMap, $sourceText): void {
        $colorsMatches = [];
        preg_match_all($regexForColors, $sourceText, $colorsMatches);
        foreach ($colorsMatches[0] as $color) {
            $color = $formatting($color);
            if (key_exists($color, $colorsMap) && !key_exists($color, $usedColorsMap)) {
                $usedColorsMap[$color] = $colorsMap[$color];
            }
        }
    };

    $usedColorsWithFormat('/#\b[a-fA-F0-9]{6}\b/u', fn(string $hexFormat): string => strtoupper($hexFormat));
    $usedColorsWithFormat('/#\b[a-fA-F0-9]{3}\b/u', 'hex3ToHex6');
    $usedColorsWithFormat('/rgb\(\s*?\d{1,3},\s*?\d{1,3},\s*?\d{1,3}\s*?\)/u', 'rgbToHex6');

    uksort($usedColorsMap, fn($first, $second): int => $first <=> $second);
    return $usedColorsMap;
}

/**
 * @param array $colorsMap
 * @param string $sourceFileName
 * @param string $targetFileName
 * @return void
 */
function replaceColors(array $colorsMap, string $sourceFileName, string $targetFileName): void
{
    $textFromFile = getFileText($sourceFileName);
    $usedColorsMap = [];

    $colorName = function (string $colorCode, callable $formatting) use (&$colorsMap) {
        $nonFormattedCode = $colorCode;
        $colorCode = $formatting($colorCode);

        if (key_exists($colorCode, $colorsMap)) {
            return $colorsMap[$colorCode];
        }

        return $nonFormattedCode;
    };
    file_put_contents(
        $targetFileName,
        preg_replace_callback_array(
            [
                '/#\b[a-fA-F0-9]{6}\b/u' => fn(array $match): string => $colorName($match[0], fn(string $hexFormat): string => strtoupper($hexFormat)),
                '/#\b[a-fA-F0-9]{3}\b/u' => fn(array $match): string => $colorName($match[0], 'hex3ToHex6'),
                '/rgb\(\s*?\d{1,3},\s*?\d{1,3},\s*?\d{1,3}\s*?\)/u' => fn(array $match): string => $colorName($match[0], 'rgbToHex6')
            ],
            $textFromFile
        )
    );
}

