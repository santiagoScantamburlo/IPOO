<?php

namespace Ipoo\Tps\Tp1\Ej10;

require_once __DIR__ . '/../../../autoload.php';

$clock = new Clock([
    'hours' => 23,
    'minutes' => 59,
    'seconds' => 59,
]);

$clock->decrement();
echo "Decrementing: " . $clock . PHP_EOL;

$clock->increment();
echo "Incrementing: " . $clock . PHP_EOL;

$clock->increment();
echo "Incrementing: " . $clock . PHP_EOL;

$clock->decrement();
echo "Incrementing: " . $clock . PHP_EOL;

$clock->restart();
echo "Restarting: " . $clock . PHP_EOL;