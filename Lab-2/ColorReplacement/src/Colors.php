<?php
declare(strict_types=1);

function readColorsFromFile(string $fileName): array
{
    $textFromFile = file_get_contents($fileName);
    $matchString = [];
    preg_match_all("/.*? #([A-F]|[0-9]){6}/u", $textFromFile, $matchString);
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

    $standardFormatOfColor = function($colorReg): string { //function for formatting colors like #FFF or rgb(255, 255, 255) to standard six-digit format
      if (strlen($colorReg) == 7) return strtoupper($colorReg); //standard format
      if (strlen($colorReg) == 4) { #FFF format
          $firstColorChar = substr($colorReg, 1, 1);
          $firstColorChar .= $firstColorChar;
          $secondColorChar = substr($colorReg, 2, 1);
          $secondColorChar .= $secondColorChar;
          $thirdColorChar = substr($colorReg, 3, 1);
          $thirdColorChar .= $thirdColorChar;
          $colorReg = '#' . $firstColorChar . $secondColorChar . $thirdColorChar;
          return strtoupper($colorReg);
      }
      //rgb(255, 255, 255) format
      $colorNumbers = [];

      preg_match_all('/\d{1,3}/u', $colorReg, $colorNumbers);
      $colorNumbers[0][0] = dechex((int)$colorNumbers[0][0]);
      if (strlen($colorNumbers[0][0]) == 1) $colorNumbers[0][0] = '0' . $colorNumbers[0][0];

      $colorNumbers[0][1] = dechex((int)$colorNumbers[0][1]);
      if (strlen($colorNumbers[0][1]) == 1) $colorNumbers[0][1] = '0' . $colorNumbers[0][1];

      $colorNumbers[0][2] = dechex((int)$colorNumbers[0][2]);
      if (strlen($colorNumbers[0][2]) == 1) $colorNumbers[0][2] = '0' . $colorNumbers[0][2];

      $colorReg = '#' . $colorNumbers[0][0] . $colorNumbers[0][1] . $colorNumbers[0][2];
      return strtoupper($colorReg);
    };

    $colorToChangeMap = []; //associative array with patterns(keys) for every found color to callables which return color value (values)
    foreach ($matchColors[0] as $currentColor) { //replacing of all founded colors
        $currentNonFormattedColor = $currentColor; //variable for replacing with regex
        $currentFormattedColor = $standardFormatOfColor($currentColor); //variable for checking the color in colorsMap
        if (key_exists($currentFormattedColor, $colorsMap)) {
            if (!key_exists($currentFormattedColor, $usedColorsMap)) $usedColorsMap[$currentFormattedColor] = $colorsMap[$currentFormattedColor]; //add used color, if still hadn't him

            $currentColorPattern = '/' . $currentNonFormattedColor . '/u';
            $currentColorPattern = str_replace('(', '\(', $currentColorPattern);
            $currentColorPattern = str_replace(')', '\)', $currentColorPattern);
            if (!key_exists($currentColorPattern, $colorToChangeMap)) {
                $colorToChangeMap[$currentColorPattern] = function ($match) use ($colorsMap, $standardFormatOfColor, $currentColorPattern) {
                    return $colorsMap[$standardFormatOfColor($match[0])]; //add for current color function which returns color value
                };
            }
        }
    }

    file_put_contents($targetFileName, preg_replace_callback_array($colorToChangeMap, $textFromFile));

    $cmp = function ($first, $second): int
    { //comparison function for sort usedColors by keys
        $first = substr($first, 1);
        $second = substr($second, 1);
        $first = (int)hexdec($first);
        $second = (int)hexdec($second);
        return $first <=> $second;
    };

    uksort($usedColorsMap, $cmp);
    return $usedColorsMap;
}

