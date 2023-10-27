<?php

declare(strict_types=1);
namespace App;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;


class Api
{

    private string $apiOne;
    private string $apiTwo;
    private string $apiThree;


    public function __construct()
    {
        $this->apiOne = 'http://api.exchangeratesapi.io/v1/latest?access_key=';
        $this->apiTwo = 'https://api.fastforex.io/fetch-multi?from=EUR&to=';
        $this->apiThree = 'https://api.freecurrencyapi.com/v1/latest?apikey=';
    }

    public function search(float $amountToConvert, string $convertFrom, string $convertTo)
    {

        // API One
        $client = new Client();
        $apiKeyOne = $_ENV['API_KEY_ONE'];
        $urlOne = $this->apiOne . $apiKeyOne . '&symbols=' . urlencode($convertFrom) . ',' . urlencode($convertTo);
        try {
            $responseOne = $client->get($urlOne);
            $dataOne = json_decode($responseOne->getBody()->getContents());
            //var_dump($dataOne);
        } catch (GuzzleException $e) {
            echo "Error fetching data from API One: " . $e->getMessage();
        }

        // API Two
        $clientTwo = new Client([
            'verify' => 'C:/CA certificates/cacert.pem',
        ]);
        $apiKeyTwo = $_ENV['API_KEY_TWO'];
        $urlTwo = $this->apiTwo . urlencode($convertFrom) . '%2C' . urlencode($convertTo) . '&api_key=' . urlencode($apiKeyTwo);
        try {
            $responseTwo = $clientTwo->get($urlTwo);
            $dataTwo = json_decode($responseTwo->getBody()->getContents());
            //var_dump($dataTwo);
        } catch (GuzzleException $e) {
            echo "Error fetching data from API Two: " . $e->getMessage();
        }

        // API Three
        $clientThree = new Client([
            'verify' => 'C:/CA certificates/cacert.pem',
        ]);
        $apiKeyThree = $_ENV['API_KEY_THREE'];
        $urlThree = $this->apiThree . $apiKeyThree . '&currencies=EUR%2C' . urlencode($convertFrom) . '%2C' . urlencode($convertTo);
        try {
            $responseThree = $clientThree->get($urlThree);
            $dataThree = json_decode($responseThree->getBody()->getContents());
            //var_dump($dataThree);die;
        } catch (GuzzleException $e) {
            echo "Error fetching data from API Three: " . $e->getMessage();
        }

        $collection = new ConvertCollection();

        if ($dataOne !== null) {
            $collection->add(new Convert(
                $amountToConvert,
                $dataOne->rates->$convertFrom,
                $dataOne->rates->$convertTo,
                'exchangeratesapi.io'
            ));
        }

        if ($dataTwo !== null) {
            $collection->add(new Convert(
                $amountToConvert,
                $dataTwo->results->$convertFrom,
                $dataTwo->results->$convertTo,
                'fastforex.io'
            ));
        }

        if ($dataThree !== null) {
            $collection->add(new Convert(
                $amountToConvert,
                $dataThree->data->$convertFrom,
                $dataThree->data->$convertTo,
                'freecurrencyapi.com'
            ));
        }
        //var_dump($collection); die;
        return $collection;
    }
}

//1key
//2key = https://api.fastforex.io/fetch-multi?from=EUR&to=USD%2CCAD&api_key=9b90679c31-79fb67c9e2-s34pn7