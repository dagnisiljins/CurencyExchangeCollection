<?php

declare(strict_types=1);
namespace App;

class Convert
{

    private float $amount;
    private float $initial;
    private float $converted;

    public function __construct(float $amount, float $initial, float $converted)
    {
        $this->amount = $amount;
        $this->initial = $initial;
        $this->converted = $converted;
    }

    public function getCalculatedAmount(): float
    {

        return ($this->amount / $this->initial) * $this->converted;
    }
}