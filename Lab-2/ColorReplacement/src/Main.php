<?php

declare(strict_types=1);

namespace App;

$colorsFile = fopen('../resources/input/colors.txt', 'r');
// initializes colors
fclose($colorsFile);

$sourceFile = fopen('../resources/input/source.txt', 'r');
$targetFile = fopen('../resources/output/target.txt', 'w');
// reads source.txt, replaces colors, writes target.txt, collects data about replaced colors
fclose($sourceFile);
fclose($targetFile);

$usedColorsFile = fopen('../resources/output/used_colors.txt', 'w');
// writes data about replaced colors
fclose($usedColorsFile);
