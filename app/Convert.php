<?php

declare(strict_types=1);
namespace App;

class Convert
{

    private float $amount;
    private float $initial;
    private float $converted;
    private string $exchangeRateSource;

    public function __construct(float $amount, float $initial, float $converted, string $exchangeRateSource)
    {
        $this->amount = $amount;
        $this->initial = $initial;
        $this->converted = $converted;
        $this->exchangeRateSource = $exchangeRateSource;
    }

    public function getExchangeRateSource(): string
    {
        return $this->exchangeRateSource;
    }

    public function getCalculatedAmount(): float
    {

        return ($this->amount / $this->initial) * $this->converted;
    }
}