<?php

declare(strict_types=1);

function func(float $x): float
{
    return ($x * $x) - 2;
}

$a = 0.0;
$b = 2.0;
$eps = 0.0001;
$i = 0;

do {

    $x0 = ($a + $b) / 2.0;

    if (func($x0) * func($a) < 0) $b = $x0;
    else $a = $x0;

    $i++;

} while(abs(func($x0)) >= $eps);

$x0 = ($a + $b) / 2.0;
$signNum = 0; //в signNum будет храниться число верных знаков после запятой, зависит от точности eps
if ($eps <= 1) {

    $epsSt = (string)$eps;
    $n = explode( '.', $epsSt)[1];
    if (strpos($n, 'E')) $signNum = (float)substr($n, strpos($n, 'E') + 2);
    else $signNum = strlen($n);
}


printf( "Корень уравнения равен %.{$signNum}f\n", $x0);
echo "Количество итераций равно " . $i;

