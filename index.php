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

$convertedAmounts = [];
foreach ($conversion->getConvertedCurrencies() as $convertedCurrency) {
    $convertedAmounts[$convertedCurrency->getExchangeRateSource()] = $convertedCurrency->getCalculatedAmount();
}

arsort($convertedAmounts);

$highestExchangeRateSource = key($convertedAmounts);
$highestValue = reset($convertedAmounts);
echo 'Highest exchange rate source: ' . $highestExchangeRateSource . ' - ' . strtoupper($convertTo) . ' ' . $highestValue . PHP_EOL;

// Remove the highest value and display the rest in order
unset($convertedAmounts[$highestExchangeRateSource]);
echo 'Other exchange rate sources: ';
foreach ($convertedAmounts as $source => $amount) {
    echo $source . ' - ' . strtoupper($convertTo) . ' ' . $amount . ', ';
}
echo PHP_EOL;

/*$convertedAmounts = [];
foreach ($conversion->getConvertedCurrencies() as $convertedCurrency) {
    $convertedAmounts[] = $convertedCurrency->getCalculatedAmount();
}


echo 'Converted amount is: ' . strtoupper($convertTo) . ' ' . implode(', ', $convertedAmounts) . PHP_EOL;
*/