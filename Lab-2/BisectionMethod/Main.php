<?php

declare(strict_types=1);

require_once('Bisection.php');

$a = -2;
$b = 2;
$eps = 1E-4;
$y = fn($x) => $x * $x - 2;
try {
    $rootsArray = bisectionMethod($a, $b, $y, $eps);

    print("Корни уравнения:\n");

    foreach($rootsArray as $element) {
        print(round($element->root, signsNum($eps)) . ', затрачено ' . $element->iterations . " итераций\n");
    }
} catch (InvalidRangeException $er) {
    print ($er->getMessage());
}


