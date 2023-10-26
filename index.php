<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';
use App\Api;

$convertRequest = readline('Enter the amount and currency to convert from(Ex.100 USD): ');
list($amount, $currency) = explode(' ', $convertRequest);

$convertTo = readline('Enter the currency to convert to: ');

$amountToConvert = 0;
$convertFrom = 0;


$apiFetcher = new Api();
$conversion = $apiFetcher->search((float)$amount, strtoupper($currency), strtoupper($convertTo));
//var_dump($conversion);

$convertedAmount = $conversion->getCalculatedAmount();
echo 'Converted amount is: ' . strtoupper($convertTo) . ' ' . round($convertedAmount, 2) . PHP_EOL;