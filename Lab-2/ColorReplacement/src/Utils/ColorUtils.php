<?php

declare(strict_types=1);

namespace App\Utils;

class ColorUtils{

    /**
     * @param string $colorString
     * @return string
     */
    public static function rgbToHex6(string $colorString): string
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
     * @param string $colorString
     * @return string
     */
    public static function hex3ToHex6(string $colorString): string
    {
        $colorStringFormatted = '#' . $colorString[1] . $colorString[1];
        $colorStringFormatted .= $colorString[2] . $colorString[2];
        $colorStringFormatted .= $colorString[3] . $colorString[3];

        return strtoupper($colorStringFormatted);
    }
}