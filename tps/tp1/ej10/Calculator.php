<?php

namespace Ipoo\Tps\Tp1\Ej10;

use Ipoo\Src\BaseClass;

/**
 * @property float $a
 * @property float $b
 * 
 * @method float add()
 * @method float subtract()
 * @method float multiply()
 * @method float divide()
 */
class Calculator extends BaseClass
{
    protected array $attributes = ['a', 'b'];

    public function add(): float
    {
        return $this->a + $this->b;
    }

    public function subtract(): float
    {
        return $this->a - $this->b;
    }

    public function multiply(): float
    {
        return $this->a * $this->b;
    }

    public function divide(): float
    {
        if ($this->b === 0) {
            throw new \DivisionByZeroError("Cannot divide by zero.");
        }
        return $this->a / $this->b;
    }
}
