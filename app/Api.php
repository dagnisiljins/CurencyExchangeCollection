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
        $this->apiOne = 'http://api.exchangeratesapi.io/v1/latest?access_key=4755858ee8f52d30b1b91c8d9c04a098&symbols=';
        $this->apiTwo = 'https://api.fastforex.io/fetch-multi?from=EUR&to=';
        $this->apiThree = 'https://api.freecurrencyapi.com/v1/latest?apikey=fca_live_yJgcD8lZ913Yvczl76XOctSY8H3QAJYDLhj0yb8F';
    }

    public function search(float $amountToConvert, string $convertFrom, string $convertTo)
    {

        $client = new Client();

        // API One
        $urlOne = $this->apiOne . urlencode($convertFrom) . ',' . urlencode($convertTo);
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
        $apiKeyTwo = '9b90679c31-79fb67c9e2-s34pn7';
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
        $urlThree = $this->apiThree . '&currencies=EUR%2C' . urlencode($convertFrom) . '%2C' . urlencode($convertTo);
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
                $dataOne->rates->$convertTo
            ));
        }

        if ($dataTwo !== null) {
            $collection->add(new Convert(
                $amountToConvert,
                $dataTwo->results->$convertFrom,
                $dataTwo->results->$convertTo
            ));
        }

        if ($dataThree !== null) {
            $collection->add(new Convert(
                $amountToConvert,
                $dataThree->data->$convertFrom,
                $dataThree->data->$convertTo
            ));
        }
        //var_dump($collection); die;
        return $collection;
    }
}

//1key
//2key = https://api.fastforex.io/fetch-multi?from=EUR&to=USD%2CCAD&api_key=9b90679c31-79fb67c9e2-s34pn7