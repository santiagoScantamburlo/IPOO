<?php

namespace Ipoo\Tps\Tp1\Ej14;

require_once __DIR__ . '/../../../autoload.php';

$bankAccount = new BankAccount([
    'accountNumber' => '123456789',
    'balance' => 1000.0,
    'ownerDni' => '12345678A',
    'annualInterestRate' => 5.0,
]);

echo "Initial balance: " . $bankAccount->getBalanceAttribute() . "\n";
$bankAccount->updateBalance();
echo "Balance after interest: " . $bankAccount->getBalanceAttribute() . "\n";
$bankAccount->deposit(500.0);
echo "Balance after deposit: " . $bankAccount->getBalanceAttribute() . "\n";
$withdrawalSuccess = $bankAccount->withdraw(200.0);
echo "Withdrawal success: " . ($withdrawalSuccess ? 'Yes' : 'No') . "\n";
echo "Final balance: " . $bankAccount->getBalanceAttribute() . "\n";