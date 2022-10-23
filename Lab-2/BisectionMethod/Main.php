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
    $rootWithIts = bisectionMethod($a, $b, $y, $eps);

    echo 'Корень уравнения на отрезке [0; 2] равен ';
    printWithTrueSigns($rootWithIts['root'], $eps);
    echo "\nЧисло итераций равно {$rootWithIts['iterations']}\n";
} catch (Exception $e) {
    print ($e->getMessage());
}

//Проверка для случая нескольких корней (тут их 2). Функция та же, но отрезок [-2; 2]

$a = -2;
echo "Поиск корней уравнения на отрезке [-2; 2]\n";

$roots = findAllRoots($a, $b, $y);

if ($roots == null) {
    echo 'Корней нет';
} else {

    echo 'Найденные корни уравнения: ';

    foreach ($roots as $root) {
        printWithTrueSigns($root);
        echo ', ';
    }

    echo "\n";
}


