<?php

declare(strict_types=1);

$names = ['Bob', 'Joe', 'Nick', 'Steve', 'Jack'];
$randomName = rand(0, 100) < 30 ? $names[rand(0, count($names) - 1)] : null;

echo 'Hello, ' . ($randomName ?? 'World') . "!\n";