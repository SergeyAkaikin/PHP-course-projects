<?php

namespace MusicService\Utils;

class DataUtils
{
    /**
     * @param string $fileName
     * @return \Generator
     */
    public static function readText(string $fileName): \Generator
    {
        $file = fopen($fileName, 'rb');
        while(($line = fgets($file)) !== false) {
            yield $line;
        }
        fclose($file);
    }

    /**
     * @param string $fileName
     * @return \Generator
     */
    public static function writeText(string $fileName): \Generator
    {
        $file = fopen($fileName, 'ab');
        while(($line = yield) !== false) {
            fwrite($file, $line);
        }
        fclose($file);
    }

}