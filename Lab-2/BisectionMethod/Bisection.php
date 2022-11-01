<?php
declare(strict_types=1);

class NoneOrManyRootsException extends Exception{

}
class InvalidRangeException extends Exception {

}

/**
 * @param float $a
 * @param float $b
 * @param callable $function
 * @param float $eps
 * @return array
 * @throws InvalidRangeException
 * @throws NoneOrManyRootsException
 */
function bisectionMethod(float $a, float $b, callable $function, float $eps = 1E-4): array
{
    $iterations = 0;

    if ($a >= $b) throw new InvalidRangeException('Invalid range of line segment');

    do {
        if ($function($a) * $function($b) > 0) throw new NoneOrManyRootsException('There is no root 
            or more than one root in line segment');

        $x0 = ($a + $b) / 2.0;

        if ($function($x0) * $function($a) < 0) $b = $x0;
        else $a = $x0;

        $iterations++;

    } while (abs($function($x0)) >= $eps);

    $x0 = ($a + $b) / 2.0;

    return ["root" => $x0, "iterations" => $iterations];
}

/*  Далее реализован метод поиска всех корней с помощью метода бисекции, следует отметить,
    что он всё ещё может пропускать некоторые корни: если более одного корня будут лежать
    в одном из отрезков $step (внутри отрезка [a, b]) - точность поиска корней, чем меньше $step,
    тем выше вероятность, что ни один корень не будет потерян, но тем более будут расти временные
    затраты */

/**
 * @param float $a
 * @param float $b
 * @param callable $function
 * @param float $eps
 * @param float $step
 * @return array|null
 * @throws InvalidRangeException
 */
function findAllRoots(
    float    $a,
    float    $b,
    callable $function,
    float    $eps = 1E-4,
    float    $step = 0.1
): ?array
{
    $x = [];
    $iterations = 0;
    for ($x1 = $a; $x1 < $b; $x1 += $step) {
        $x2 = $x1 + $step;

        if ($function($x1) * $function($x2) < 0) { //отрезки, внутри которых нуль
            // или более 1 корней не рассматриваются
            try {
                $sectionRoot = BisectionMethod($x1, $x2, $function, $eps);

                $x[] = $sectionRoot['root'];
                $iterations += $sectionRoot['iterations'];

            } catch (NoneOrManyRootsException $e) { //отрезок, не содержащий в себе корня уравнения
            }
        }

    }
    if (!empty($x))
        echo "Всего было затрачено {$iterations} итераций\n";

    return $x;
}

//Функция, возвращающая число верных знаков после запятой, исходя из точности eps
/**
 * @param float $eps
 * @return int
 */
function signsNum(float $eps = 1E-4): int
{
    $signsNum = 0; //число верных знаков
    if ($eps < 1) {

        $epsString = (string)$eps;
        $afterDot = explode('.', $epsString)[1];
        if (str_contains($afterDot, 'E')) $signsNum = (int)substr($afterDot, strpos($afterDot, 'E') + 2);
        else $signsNum = strlen($afterDot);
    }

    return $signsNum;
}

