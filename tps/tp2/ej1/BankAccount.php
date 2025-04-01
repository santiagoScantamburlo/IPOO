<?php

namespace Ipoo\Tps\Tp2\Ej1;

use Ipoo\BaseClass;

define('DAYS_IN_CURRENT_YEAR', (date('Y') % 4 == 0 && (date('Y') % 100 != 0 || date('Y') % 400 == 0)) ? 366 : 365);

/**
 * Class BankAccount
 * 
 * @property string $accountNumber
 * @property float $balance
 * @property Person $owner
 * @property float $annualInterestRate
 */
class BankAccount extends BaseClass
{
    protected array $attributes = ['accountNumber', 'balance', 'owner', 'annualInterestRate'];
    private const DAYS_IN_YEAR = DAYS_IN_CURRENT_YEAR;

    public function updateBalance(): void
    {
        $interest = ($this->annualInterestRate / 100) * ($this->balance / self::DAYS_IN_YEAR);
        $this->balance += $interest;
    }

    public function deposit(float $amount): void
    {
        $this->balance += $amount;
    }

    public function withdraw(float $amount): bool
    {
        if ($this->balance < $amount) return false;

        $this->balance -= $amount;
        return true;
    }

    public function getBalanceAttribute(): string
    {
        return "$ " . number_format($this->balance, 2, '.', '');
    }
}
