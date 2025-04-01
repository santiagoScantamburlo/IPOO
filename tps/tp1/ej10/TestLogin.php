<?php

namespace Ipoo\Tps\Tp1\Ej10;

require_once __DIR__ . '/../../../autoload.php';

$login = new Login([
    'username' => 'user',
    'password' => '1234',
]);

echo "Pasword 1234 " . ($login->changePassword('1234') ? "changed\n" : "not changed\n");
echo "Pasword 12345 " . ($login->changePassword('12345') ? "changed\n" : "not changed\n");
echo "Pasword 1234 " . ($login->changePassword('1234') ? "changed\n" : "not changed\n");
echo "Pasword 123456 " . ($login->changePassword('123456') ? "changed\n" : "not changed\n");
echo "Pasword 12345 " . ($login->changePassword('12345') ? "changed\n" : "not changed\n");
echo "Pasword 1234567 " . ($login->changePassword('1234567') ? "changed\n" : "not changed\n");
echo "Pasword 123456 " . ($login->changePassword('123456') ? "changed\n" : "not changed\n");
echo "Pasword 12345678 " . ($login->changePassword('12345678') ? "changed\n" : "not changed\n");
echo "Pasword 1234 " . ($login->changePassword('1234') ? "changed\n" : "not changed\n");