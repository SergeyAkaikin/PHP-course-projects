<?php

declare(strict_types=1);

namespace  App;

require_once __DIR__ . '/../vendor/autoload.php';

use App\DataAccess\ColorStorage;
use App\DataAccess\TextStorage;
use App\Services\ColorReplacer;
use App\Utils\DataUtils;

$colorsFile = '../resources/input/colors.txt';
$usedColorsFile = '../resources/output/used_colors.txt';

$colorStorage = new ColorStorage(
    DataUtils::readColorsSource($colorsFile),
    DataUtils::writeDataSource($usedColorsFile)
);

$colorsMap = $colorStorage->readColorsMap();



$sourceFile = '../resources/input/source.txt';
$targetFile = '../resources/output/target.txt';

$colorReplacer = new ColorReplacer();



$sourceTextReader = DataUtils::readDataSource($sourceFile);
$sourceTextWriter = DataUtils::writeDataSource($targetFile);

$usedColorsMap = [];
foreach ($sourceTextReader as $sourceText) {
    $text = $colorReplacer->replaceColors($sourceText, $colorsMap, $usedColorsMap);
    $sourceTextWriter->send($text);
}

$colorStorage->writeColorsMap($usedColorsMap);











