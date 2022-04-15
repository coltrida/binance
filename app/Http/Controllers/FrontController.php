<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use KriosMane\CoinMarketCap\Facades\CoinMarketCap;

class FrontController extends Controller
{
    public function index()
    {
      //  $binance = new \sabramooz\binance\BinanceAPI();
      //   return dump($binance->getRecentTrades());

        /*return Http::get('https://api.binance.com/api/v3/depth', [
            'symbol' => 'BTCUSDT',
            'limit' => '1'
        ]);*/

        /*return Http::get('https://api.binance.com/api/v3/exchangeInfo', [
            'symbol' => 'BTCUSDT'
        ]);*/

        /*return Http::get('https://api.binance.com/api/v3/trades', [
            'symbol' => 'BTCUSDT',
            'limit' => '5'
        ]);*/

        /*return Http::get('https://api.binance.com/api/v3/klines', [
            'symbol' => 'BTCUSDT',
            'interval' => '100'
        ]);*/

        /*return Http::get('https://api.binance.com/api/v3/avgPrice', [
            'symbol' => 'BTCUSDT',
        ]);*/
/*
        $url = 'https://sandbox-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        $parameters = [
            'start' => '1',
            'limit' => '5000',
            'convert' => 'USD'
        ];

        $headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: cb6fa98c-3fc6-4a65-8311-5c4a86ae580d'
        ];
        $qs = http_build_query($parameters); // query string encode the parameters
        $request = "{$url}?{$qs}"; // create the request URL


        $curl = curl_init(); // Get cURL resource
// Set cURL options
        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,            // set the request URL
            CURLOPT_HTTPHEADER => $headers,     // set the headers
            CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));

        $response = curl_exec($curl); // Send the request, save the response
        print_r(json_decode($response)); // print json decoded response
        curl_close($curl); // Close request*/

        /*return Http::get('https://api.binance.com/api/v3/depth', [
            'symbol' => 'BTCUSDT',
            'limit' => '5'
        ]);*/

       // return CoinMarketCap::all_cryptos();

        /*$btcusdt = Http::get('https://api.binance.com/api/v3/ticker/price', [
            'symbol' => 'BTCUSDT'
        ]);*/

        return view('home');
    }

    public function info()
    {
        $btcusdt = Http::get('https://api.binance.com/api/v3/ticker/price', [
            'symbol' => 'SHIBUSDT'
        ]);
        return $btcusdt;
    }
}
