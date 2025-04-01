<?php

namespace Ipoo\Tps\Tp1\Ej10;

require_once __DIR__ . '/../../../autoload.php';

$customDate = new CustomDate([
    'day' => 15,
    'month' => 8,
    'year' => 2023,
]);

echo "Initial Date: $customDate \n";

$customDate->incrementDay();
echo "After Incrementing Day: $customDate \n";

$customDate->incrementByDays(20);
echo "After Incrementing 20 Days: $customDate\n";

$customDate->decrementDay();
echo "After Decrementing Day: $customDate\n";

$customDate->fill([
    'day' => 1,
    'month' => 2,
    'year' => 2024,
]);

echo "After Restarting Date: $customDate\n";
echo "Is Leap Year: " . ($customDate->isLeapYear() ? 'Yes' : 'No') . PHP_EOL;

echo $customDate->incrementByDays(28) . PHP_EOL;
echo $customDate->extendedDate() . PHP_EOL;