<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\DataAccess\ColorStorage;
use App\Utils\DataUtils;
use App\Services\ColorReplacer;
use PHPUnit\Framework\TestCase;

class ColorsTests extends TestCase
{
    /**
     * @dataProvider validInputColorFileProvider
     */
    public function test_readColorsMap_validInputColors_isFullColorsMap(string $fileName, array $expectedResult): void
    {
        $colorsReader = new ColorStorage(
            DataUtils::readColorsSource($fileName),
            DataUtils::writeDataSource('stubFileName')
        );

        $getResult = $colorsReader->readColorsMap();
        $this->assertEquals($expectedResult, $getResult);
    }

    public function validInputColorFileProvider(): array
    {
        return [
            ['testFiles/testInput/colorsTestWithCorrectValuesFile1.txt', ['#20B2AA' => 'FirstColor', '#CD5C5C' => 'SecondColor', '#8B4513' => 'ThirdColor',
                '#CD853F' => 'FourthColor', '#FF00FF' => 'FifthColor', '#FFFF00' => 'SixthColor']],
            ['testFiles/testInput/colorsTestWithCorrectValuesFile2.txt', ['#9370DB' => 'MediumPurple', '#00FA9A' => 'MedSpringGreen', '#FFFFE0' => 'LightYellow', '#CD853F' => 'Peru']]
        ];
    }

    public function test_readColorsMap_noneCorrectColorsInFile_emptyColorsMap(): void
    {
        $fileName = 'testFiles/testInput/colorsTestWithNoneCorrectValuesFile.txt';
        $colorReader = new ColorStorage(
            DataUtils::readColorsSource($fileName),
            DataUtils::writeDataSource('stubFileName')
        );
        $getResult = $colorReader->readColorsMap();
        $this->assertEmpty($getResult);
    }

    public function test_readColorsMap_incorrectAndCorrectColorsInFile_correctColorsMap(): void
    {
        $fileName = 'testFiles/testInput/colorsTestWithDifferentFormatsFile.txt';
        $colorsReader = new ColorStorage(
            DataUtils::readColorsSource('testFiles/testInput/colorsTestWithDifferentFormatsFile.txt'),
            DataUtils::writeDataSource('stubFileName')
        );
        $getResult = $colorsReader->readColorsMap();
        $expectedResult = ['#B8860B' => 'FirstCorrectColor', '#BC8F8F' => 'SecondCorrectColor', '#CD5C5C' => 'ThirdCorrectColor',
            '#CD853F' => 'FourthCorrectColor', '#DEB887' => 'FifthCorrectColor', '#F4A460' => 'SixthCorrectColor'];
        $this->assertEquals($expectedResult, $getResult);
    }

    /**
     * @dataProvider validInputAndOutputFilesProvider
     */
    public function test_replaceColors_colorsWithDifferentFormatsToReplace_allColorsCorrectlyReplaced(
        array  $colorsMap,
        string $sourceFileName,
        string $expectedOutPutFile
    ): void
    {
        $usedColorsMap = [];
        $colorReplacer = new ColorReplacer();
        $sourceReader = DataUtils::readDataSource($sourceFileName);
        $replacedText = '';
        foreach ($sourceReader as $sourceLine) {
            $replacedText .= $colorReplacer->replaceColors($sourceLine, $colorsMap, $usedColorsMap);
        }
        $expectedFileReader = DataUtils::readDataSource($expectedOutPutFile);
        $expectedText = '';
        foreach ($expectedFileReader as $expectedLine) {
            $expectedText .= $expectedLine;
        }

        $this->assertEquals($expectedText, $replacedText);
    }

    public function validInputAndOutputFilesProvider(): array
    {
        return [
            [
                ['#20B2AA' => 'FirstColor', '#CD5C5C' => 'SecondColor', '#8B4513' => 'ThirdColor', '#CD853F' => 'FourthColor', '#FF00FF' => 'FifthColor', '#FFFF00' => 'SixthColor'],
                'testFiles/testInput/replaceTestSourceFile1.txt',
                'testFiles/testOutput/expectedFile1.txt'
            ]
        ];
    }

}