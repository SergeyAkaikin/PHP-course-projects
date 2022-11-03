<?php

declare(strict_types=1);

namespace App;
require_once('Colors.php');

$colorsFile = '../resources/input/colors.txt';
// reads colors from colors.txt
$colorsMap = readColorsFromFile($colorsFile);


$sourceFile = '../resources/input/source.txt';
$targetFile = '../resources/output/target.txt';
// reads source.txt, replaces colors, writes target.txt, collects data about replaced colors

$usedColorsMap = replaceAndCollectColors($colorsMap, $sourceFile, $targetFile);

$usedColorsFile = fopen('../resources/output/used_colors.txt', 'w');
// writes data about replaced colors
foreach ($usedColorsMap as $key => $value) {
    fwrite($usedColorsFile, $key . ' ' . $value . "\n");
}
fclose($usedColorsFile);