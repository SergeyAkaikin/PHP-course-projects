<?php
    declare(strict_types = 1);
    function BisectionMethod(float $a, float $b, callable $function, float $eps = 1E-4): array {
        $iterations = 0;

        do {
            if ($function($a) * $function($b) > 0) throw new Exception('There is no root 
            or more than one root in line segment');

            $x0 = ($a + $b) / 2.0;

            if ($function($x0) * $function($a) < 0) $b = $x0;
            else $a = $x0;

            $iterations++;

        } while(abs($function($x0)) >= $eps);

        $x0 = ($a + $b) / 2.0;

        return ["root" => $x0, "iterations" => $iterations];
    }

    /*  Далее реализован метод поиска всех корней с помощью метода бисекции, следует отметить,
        что он всё ещё может пропускать некоторые корни: если более одного корня будут лежать
        в одном из отрезков $step (внутри отрезка [a, b]) - точность поиска корней, чем меньше $step,
        тем выше вероятность, что ни один корень не будет потерян, но тем более будут расти временные
        затраты */

    function findAllRoots(
                          float $a,
                          float $b,
                          callable $function,
                          float $eps = 1E-4,
                          float $step = 0.1
    ): ?array {
        $x = [];
        $iterations = 0;
        for ( $x1 = $a; $x1 < $b; $x1 += $step ) {
            $x2 = $x1 + $step;

            if ( $function($x1) * $function($x2) < 0 ) { //отрезки, внутри которых нуль
                                                         // или более 1 корней не рассматриваются
                try {
                    $sectionRoot = BisectionMethod($x1, $x2, $function, $eps);

                    $x[] = $sectionRoot['root'];
                    $iterations += $sectionRoot['iterations'];

                } catch (Exception $e) {
                    echo "Some mistake\n";
                }
            }

        }
        if ( !empty($x) )
            echo "Всего было затрачено {$iterations} итераций\n";

        return $x;
    }

    //Функция форматированного вывода с верными знаками после запятой
    function printWithTrueSigns(float $number, float $eps = 1E-4): void {
        $signNum = 0; //число верных знаков
        if ($eps <= 1) {

            $epsSt = (string)$eps;
            $n = explode( '.', $epsSt)[1];
            if (strpos($n, 'E')) $signNum = (float)substr($n, strpos($n, 'E') + 2);
            else $signNum = strlen($n);
        }
        printf( "%.{$signNum}f", $number);
    }

?>