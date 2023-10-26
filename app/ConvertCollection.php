<?php

declare(strict_types=1);
namespace App;

class ConvertCollection
{
    private array $convertedCurrencies;

    public function __construct(array $convertedCurrencies = [])
    {
        foreach ($convertedCurrencies as $convertedCurrency)
        $this->add($convertedCurrency);
    }

    public function add(Convert $convertedCurrency)
    {
        $this->convertedCurrencies [] = $convertedCurrency;
    }

    public function getConvertedCurrencies(): array
    {
        return $this->convertedCurrencies;
    }
}