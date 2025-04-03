<?php

namespace Ipoo\Tps\Tp1\Ej10;

use Ipoo\Src\BaseClass;

/**
 * @property int $hours
 * @property int $minutes
 * @property int $seconds
 * 
 * @method void increment()
 * @method void decrement()
 * @method void restart()
 */
class Clock extends BaseClass
{
    protected array $attributes = ['hours', 'minutes', 'seconds'];

    public function increment()
    {
        $this->seconds++;
        if ($this->seconds >= 60) {
            $this->seconds = 0;
            $this->minutes++;
            if ($this->minutes >= 60) {
                $this->minutes = 0;
                $this->hours++;
                if ($this->hours >= 24) {
                    $this->hours = 0;
                }
            }
        }
    }

    public function decrement()
    {
        $this->seconds--;
        if ($this->seconds < 0) {
            $this->seconds = 59;
            $this->minutes--;
            if ($this->minutes < 0) {
                $this->minutes = 59;
                $this->hours--;
                if ($this->hours < 0) {
                    $this->hours = 23;
                }
            }
        }
    }

    public function restart()
    {
        $this->fill([
            'hours' => 0,
            'minutes' => 0,
            'seconds' => 0,
        ]);
    }

    public function __toString()
    {
        return sprintf('%02d:%02d:%02d', $this->hours, $this->minutes, $this->seconds);
    }
}
