<?php

namespace Ipoo\Tps\Tp2\Ej1;

require_once __DIR__ . '/../../../autoload.php';

/** Creating a new instance of the Person class and setting the values of the attributes */

$person = new Person([
    'name' => 'John',
    'surname' => 'Doe',
    'documentType' => 'DNI',
    'documentNumber' => '12345678',
]);

$attributes = $person->get([
    'name',
    'surname',
    'documentType',
    'documentNumber',
]);

$person->fill([
    'name' => 'Jane',
    'surname' => 'Smith',
    'documentType' => 'DNI',
    'documentNumber' => '87654321',
]);

$bankAccount = new BankAccount([
    'accountNumber' => '1234567890',
    'balance' => 1000.00,
    'owner' => $person,
    'annualInterestRate' => 5.0,
]);

echo $bankAccount;
