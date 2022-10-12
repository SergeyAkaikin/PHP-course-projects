<?php

declare(strict_types=1);

function func(float $x): float
{
    return ($x * $x) - 2;
}

$a = 0.0;
$b = 2.0;
$eps = 0.0001;

$y = func(($b - $a) / 2);

echo "Корень уравнения равен {$y}\n";
echo "Количество итераций равно " . 1;
