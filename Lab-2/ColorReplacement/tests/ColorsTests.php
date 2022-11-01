<?php
declare(strict_types=1);

require_once '../src/Colors.php';

use PHPUnit\Framework\TestCase;

class ColorsTests extends TestCase
{
    /**
     * @dataProvider validInputColorFileProvider
     */


    public function test_readColorsFromFile_correctColorsInFile_isFullColorMap(string $fileName, array $expectedResult): void
    {
        $getResult = readColorsFromFile($fileName);
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

    public function test_readColorsFromFile_noneCorrectColorsInFile_emptyColorMap(): void
    {
        $getResult = readColorsFromFile('testFiles/testInput/colorsTestWithNoneCorrectValuesFile.txt');
        $this->assertEmpty($getResult);
    }

    public function test_readColorsFromFile_incorrectAndCorrectColorsInFile_correctColorsMap(): void
    {
        $getResult = readColorsFromFile('testFiles/testInput/colorsTestWithDifferentFormatsFile.txt');
        $expectedResult = ['#B8860B' => 'FirstCorrectColor', '#BC8F8F' => 'SecondCorrectColor', '#CD5C5C' => 'ThirdCorrectColor',
            '#CD853F' => 'FourthCorrectColor', '#DEB887' => 'FifthCorrectColor', '#F4A460' => 'SixthCorrectColor'];
        $this->assertEquals($expectedResult, $getResult);
    }

    /**
     * @dataProvider validInputAndOutputFilesProvider
     */

    public function test_replaceAndCollectColors_colorsWithDifferentFormatsToReplace_allColorsCorrectlyReplaced(
        array $colorsMap,
        string $sourceFileName,
        string $targetFileName,
        array $expectedUsedColorsMap,
        string $expectedOutPutFile
    ): void {
        $getUsedColorsMap = replaceAndCollectColors($colorsMap, $sourceFileName, $targetFileName);
        $this->assertEquals($expectedUsedColorsMap, $getUsedColorsMap);
        $expectedFileText = file_get_contents($expectedOutPutFile);
        $getTargetFileText = file_get_contents($targetFileName);
        $this->assertEquals($expectedFileText, $getTargetFileText);
    }
    public function validInputAndOutputFilesProvider(): array {
        return [
            [
                ['#20B2AA' => 'FirstColor', '#CD5C5C' => 'SecondColor', '#8B4513' => 'ThirdColor', '#CD853F' => 'FourthColor',  '#FF00FF' => 'FifthColor', '#FFFF00' => 'SixthColor'],
                'testFiles/testInput/replaceTestSourceFile1.txt',
                'testFiles/testOutput/resultTestFile1.txt',
                ['#CD5C5C' => 'SecondColor', '#FF00FF' => 'FifthColor', '#CD853F' => 'FourthColor', '#20B2AA' => 'FirstColor', '#FFFF00' => 'SixthColor'],
                'testFiles/testOutput/expectedFile1.txt'
            ]
        ];
    }

    public function test_replaceAndCollectColors_noneReplacedColors_emptyUsedColorsMap() {
        $getColorsMap = readColorsFromFile('testFiles/testInput/colorsTestWithCorrectValuesFile2.txt');
        $getUsedColorsMap = replaceAndCollectColors($getColorsMap, 'testFiles/testInput/replaceTestSourceFile2.txt', 'testFiles/testOutput/resultTestFile2.txt');
        var_dump($getUsedColorsMap);
        $this->assertEmpty($getUsedColorsMap);
    }
}