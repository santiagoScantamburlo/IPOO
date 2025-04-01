<?php

namespace Ipoo\Tps\Tp1\Ej10;

require_once __DIR__ . '/../../../autoload.php';

$calculator = new Calculator([
    'a' => 10,
    'b' => 5,
]);

echo "Addition: " . $calculator->get('a') . " + " . $calculator->get('b') . " = " . $calculator->add() . PHP_EOL;
echo "Subtraction: " . $calculator->get('a') . " - " . $calculator->get('b') . " = " . $calculator->subtract() . PHP_EOL;
echo "Multiplication: " . $calculator->get('a') . " * " . $calculator->get('b') . " = " . $calculator->multiply() . PHP_EOL;
echo "Division: " . $calculator->get('a') . " / " . $calculator->get('b') . " = " . $calculator->divide() . PHP_EOL;
