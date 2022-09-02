<?php

namespace Zedium\Classes;

class RemoteCall
{
    private $endpoint;
    private $parameters;
    private $headers;
    private $coins = [];
    public function __construct(){

        $this->endpoint = 'https://pro-api.coinmarketcap.com/v2/cryptocurrency/quotes/latest';

        $this->parameters = [

            'convert' => 'USD',
            'symbol'=> implode(',', $this->getCoinsFromDB())
        ];

        $this->headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: ' . COIN_MARKET_CAP_API_TOKEN
        ];
    }

    public function doRequest(){
        $qs = http_build_query($this->parameters);
        $request = "{$this->endpoint}?{$qs}";


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,            // set the request URL
            CURLOPT_HTTPHEADER => $this->headers,     // set the headers
            CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));

        $response = curl_exec($curl); // Send the request, save the response
        $result = (json_decode($response)); // print json decoded response

        curl_close($curl);
        return $this->parseCoinData($result);
    }

    private function getCoinsFromDB(){
        $database = CustomDB::getInstance();
        $results = $database->getCoinList();
        $this->coins = [];
        foreach($results as $result){
            $this->coins[] = $result['short_name'];
        }
        return $this->coins;
    }

    private function parseCoinData($json){

        if(empty($json->data))
            return;

        $data = $json->data;

        $structured_coins = [];

        foreach($this->coins as $coin){
            if( isset($data->{$coin})){
                $coinObject = $data->{$coin};

                if(empty($coinObject))
                    continue;

                $stdClass = new \stdClass();
                $stdClass->short_name = $coin;
                $stdClass->usd_price = $coinObject[0]->quote->USD->price;
                $stdClass->market_cap = $coinObject[0]->quote->USD->market_cap;
                $stdClass->last_update = $coinObject[0]->quote->USD->last_updated;

                $structured_coins[] = $stdClass;
            }
        }

        return $structured_coins;
    }
}