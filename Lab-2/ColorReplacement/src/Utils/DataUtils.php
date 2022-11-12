<?php

declare(strict_types=1);

namespace App\Utils;

use Generator;

class DataUtils
{
    /**
     * @param string $fileName
     * @return Generator
     */
    public static function readDataSource(string $fileName): Generator
    {
        $file = fopen($fileName, 'rb');
        while (($line = fgets($file)) !== false) {
            yield $line;
        }
        fclose($file);
    }

    /**
     * @param string $fileName
     * @return Generator
     */
    public static function writeDataSource(string $fileName): Generator
    {
        $file = fopen($fileName, 'wb');
        while (($line = yield) !== false) {
            fwrite($file, $line);
        }
        fclose($file);
    }

    /**
     * @param string $fileName
     * @return Generator
     */
    public static function readColorsSource(string $fileName): Generator
    {
        $file = fopen($fileName, 'rb');
        while (($colorString = fgets($file)) !== false) {
            $colorString = trim($colorString);
            $matchString = [];
            preg_match("/\b.*?\s#([A-F]|[0-9]){6}\b/u", $colorString, $matchString);
            $colorString = (empty($matchString)) ? '' : $matchString[0];
            $keyValuePair = explode(' ', $colorString);
            if ($keyValuePair !== null && count($keyValuePair) == 2) {
                yield strtoupper($keyValuePair[1]) => $keyValuePair[0];
            }
        }
    }

    /**
     * @param array $lines
     * @return Generator
     */
    public static function readListSource(array &$lines): Generator
    {
        foreach ($lines as $line) {
            yield $line;
        }
    }

    /**
     * @param array $lines
     * @return Generator
     */
    public static function writeListSource(array &$lines): Generator
    {
        while (($line = yield) !== false) {
            $lines[] = $line;
        }
    }


}