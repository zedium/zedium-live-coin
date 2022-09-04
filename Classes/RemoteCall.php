<?php

namespace Zedium\Classes;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * This class calls a remote API
 * It will be done through CURL request
 */
class RemoteCall
{

    /**
     * @var string endpoint url to API
     */
    private $endpoint;

    /**
     * @var array
     */
    private $parameters;
    /**
     * @var string[]
     */
    private $headers;
    /**
     * @var array
     */
    private $coins = [];


    /**
     *  Instantiate the parameteres of RemoteCall
     */
    public function __construct(){

        $this->endpoint = ZEDIUM_API_END_POINT;

        $this->parameters = [
            'convert' => 'USD',
            'symbol'=> implode(',', $this->getCoinsFromDB())
        ];

        $this->headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: ' . COIN_MARKET_CAP_API_TOKEN
        ];

    }

    /**
     * Do a request to remote API
     * Parse the data and return it
     * @return array|void
     */
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


    /**
     * Get coins short names to add them to api request
     * return will be something like [BTC,ETH,ADA,...]
     * @return array
     */
    private function getCoinsFromDB(){

        $database = CustomDB::getInstance();
        $results = $database->getCoinList();
        $this->coins = [];

        foreach($results as $result){
            $this->coins[] = $result['short_name'];
        }

        return $this->coins;
    }


    /**
     * @param $json JSON that has returned from API
     * This method parses the json and convert it to array of php stdClass
     *
     * stdClass{
     *      ->short_name
     *      ->usd_price
     *      ->market_cap
     *      ->last_update
     * }
     *
     * @return array|void
     */
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