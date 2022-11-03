<?php
declare(strict_types=1);

class InvalidRangeException extends Exception {

}

/**
 * @property-read float $root
 * @property-read int $iterations
 */
class ResultModel {
    public readonly float $root;
    public readonly int $iterations;

    /**
     * @param float $root
     * @param int $iterations
     */
    public function __construct(float $root, int $iterations)
    {
        $this->root = $root;
        $this->iterations = $iterations;
    }
}

/**
 * @param float $a
 * @param float $b
 * @param callable $function
 * @param float $eps
 * @param float $step
 * @return array
 * @throws InvalidRangeException
 */

function bisectionMethod(float $a, float $b, callable $function, float $eps = 1E-4, float $step = 0.1): array
{
    if ($a >= $b) throw new InvalidRangeException('Invalid range of line segment');

    $rootsArray = [];

    for ($x1 = $a; $x1 < $b; $x1 += $step) {
        $left = $x1;
        $right = $x1 + $step;

        if ($function($left) * $function($right) < 0) {
            $iterations = 0;

            do {
                $x0 = ($left + $right) / 2.0;

                if ($function($x0) * $function($left) < 0) {
                    $right = $x0;
                }
                else {
                    $left = $x0;
                }

                $iterations++;

            } while (abs($function($x0)) >= $eps);

            $x0 = ($left + $right) / 2.0;

            $rootsArray[] = new ResultModel($x0, $iterations);
        }
    }


    return $rootsArray;
}

/**
 * @param float $eps
 * @return int
 */

function signsNum(float $eps = 1E-4): int
{
    $signsNum = 0;
    if ($eps < 1) {

        $epsString = (string)$eps;
        $afterDot = explode('.', $epsString)[1];
        if (str_contains($afterDot, 'E')) $signsNum = (int)substr($afterDot, strpos($afterDot, 'E') + 2);
        else $signsNum = strlen($afterDot);
    }

    return $signsNum;
}

