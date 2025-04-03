<?php

namespace Ipoo\Tps\Tp1\Ej10;

use Ipoo\Src\BaseClass;

class CustomDate extends BaseClass
{
    protected array $attributes = ['day', 'month', 'year'];


    public function isLeapYear(): bool
    {
        return ($this->year % 4 === 0 && $this->year % 100 !== 0) || ($this->year % 400 === 0);
    }

    public function daysInMonth(): int
    {
        $daysInMonths = [
            1 => 31,
            2 => $this->isLeapYear() ? 29 : 28,
            3 => 31,
            4 => 30,
            5 => 31,
            6 => 30,
            7 => 31,
            8 => 31,
            9 => 30,
            10 => 31,
            11 => 30,
            12 => 31,
        ];

        return $daysInMonths[$this->month];
    }

    public function incrementByDays(int $days): void
    {
        for ($i = 0; $i < $days; $i++) {
            $this->incrementDay();
        }
    }

    public function incrementDay(): void
    {
        $this->day++;
        if ($this->day > $this->daysInMonth()) {
            $this->day = 1;
            $this->month++;
            if ($this->month > 12) {
                $this->month = 1;
                $this->year++;
            }
        }
    }

    public function decrementDay(): void
    {
        $this->day--;
        if ($this->day < 1) {
            $this->month--;
            if ($this->month < 1) {
                $this->month = 12;
                $this->year--;
            }
            $this->day = $this->daysInMonth();
        }
    }

    public function __toString(): string
    {
        return sprintf("%02d/%02d/%04d", $this->day, $this->month, $this->year);
    }

    public function extendedDate(): string
    {
        $months = [
            1 => "January",
            2 => "February",
            3 => "March",
            4 => "April",
            5 => "May",
            6 => "June",
            7 => "July",
            8 => "August",
            9 => "September",
            10 => "October",
            11 => "November",
            12 => "December"
        ];
        $monthName = $months[$this->month];

        return sprintf("%02d %s %04d", $this->day, $monthName, $this->year);
    }
}
