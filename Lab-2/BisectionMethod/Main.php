<?php

declare(strict_types=1);

require_once('Bisection.php');
//Тестовый файл для проверки работы методов файла Bisection.php

//Проверка для случая одного изолрованного корня
$a = 0;
$b = 2;
$eps = 1E-4;
$y = fn($x) => $x * $x - 2;
try {
    $rootWithIterations = bisectionMethod($a, $b, $y, $eps);

    echo 'Корень уравнения на отрезке [0; 2] равен ';
    echo round($rootWithIterations['root'], signsNum($eps));
    echo "\nЧисло итераций равно {$rootWithIterations['iterations']}\n";
} catch (InvalidRangeException $er) {
    print ($er->getMessage());
} catch (NoneOrManyRootsException $en) {
    print ($en->getMessage());
}

//Проверка для случая нескольких корней (тут их 2). Функция та же, но отрезок [-2; 2]


try {

    $a = -2;
    echo "Поиск корней уравнения на отрезке [-2; 2]\n";

    $roots = findAllRoots($a, $b, $y, $eps);

    if ($roots == null) {
        echo 'Корней нет';
    } else {

        echo 'Найденные корни уравнения: ';

        foreach ($roots as $root) {
            echo round($root, signsNum($eps));
            echo ', ';
        }

        echo "\n";
    }
} catch (InvalidRangeException $e) {
    print($e->getMessage());
}


