<?php

namespace App\Http\Controllers;

use App\Mail\WarningMail;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
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
        // carico le coins
        $coins = [];
        $percentuale = 0.2;
        $allCoins = Http::get('https://api.binance.com/api/v1/exchangeInfo');
        foreach ($allCoins->json()['symbols'] as $coin){
            if (Str::endsWith($coin['symbol'], 'USDT')){
                array_push($coins, $coin['symbol']);
            }
        }


        // lista coin
        //$coins = ['SHIBUSDT', 'BTCUSDT', 'CFXUSDT', 'RENUSDT', 'BURGERUSDT'];
        // array dei risultati
        $risultati = [];

        // ciclo ogni coin
        foreach ($coins as $coin){
            // impostare a zero la somma delle chiusure che poi mi servirà per calcolare la media
            $sommaChiusure = 0;

            // variabile che mi dirà se un valore supera i livelli di guardia e la coin diventa non interessante
            $superatoLimite = false;

            // catturo i valori degli ultimi 30 giorni
            $valori = Http::get('https://api.binance.com/api/v3/klines', [
                'symbol' => $coin,
                'interval' => '1d',
                'limit' => 30
            ]);

            // ciclo per calcolare la somma delle chiusure presenti nella quinta riga, cioè con indice 4
            foreach ($valori->json() as $value){
                $sommaChiusure += $value[4];
            }

            // calcolo la media delle chiusure
            $risultati[$coin]['media'] = $sommaChiusure / 30;

            // preparo il vettore che conterrà i valori delle chiusure degli ultimi 30 giorni
            //$risultati[$coin]['valori'] = [];

            // preparo il vettore che conterrà i valori che superano i limiti
            $risultati[$coin]['limitiSuperati'] = [];

            // ciclo per controllare se i valori di chiusura superano i limiti della media + o - del 10%
            foreach ($valori->json() as $value){
                // inserisco il valore di chiusura nel vettore
                //array_push($risultati[$coin]['valori'], $value[4]);

                // calcolo il limite inferiore = media - 10%
                $limiteDown = /*$risultati[$coin]['limiteDown'] =*/ $risultati[$coin]['media'] - ($risultati[$coin]['media'] * $percentuale);

                // calcolo il limite superiore = media + 10%
                $limiteUp = /*$risultati[$coin]['limiteUp'] =*/ $risultati[$coin]['media'] + ($risultati[$coin]['media'] * $percentuale);

                // se il valore di chiusura supera i limiti, imposto il flag a true ed inserisco il valore nel vettore dei limiti superati
                if ($value[4] < $limiteDown || $value[4] > $limiteUp){
                    $superatoLimite = true;
                    array_push($risultati[$coin]['limitiSuperati'], $value[4]);
                }
            }

            // inserisco il valore di flag
            $risultati[$coin]['superatoLimite'] = $superatoLimite;

        }

        return $risultati;
    }

    public function controllo()
    {
        $btcusdt = Http::get('https://api.binance.com/api/v3/ticker/price', [
            'symbol' => 'EURUSDT'
        ]);

        $tradesNonAperture = Trade::nonaperture()->with('platform')->get();
        $tradesAperture = Trade::aperture()->with('platform')->get();

        foreach ($tradesNonAperture as $trade){
            $delta = number_format((($trade->saldo - $btcusdt['price']) / $trade->saldo) * 100, 2, ',', '.');
            if ((float)$delta < -4){
                \Mail::to('coltrida@gmail.com')->send(new WarningMail($trade->platform->name, $delta));
                \Mail::to('coltricat75@gmail.com')->send(new WarningMail($trade->platform->name, $delta));
            }
        }

        foreach ($tradesAperture as $item){
            $simbolo = Str::substr($item->platform->name, 11);

            $coin = Http::get('https://api.binance.com/api/v3/ticker/price', [
                'symbol' => $simbolo.'USDT'
            ]);

            if(!isset($coin['price'])){

                $response = Http::withHeaders([
                    'X-RapidAPI-Host' => 'apidojo-yahoo-finance-v1.p.rapidapi.com',
                    'X-RapidAPI-Key' => '6d9727b350msh6d5d731383e0c40p166b61jsn7705b35f552b'
                ])->get('https://apidojo-yahoo-finance-v1.p.rapidapi.com/market/v2/get-quotes', [
                    'region' => 'US',
                    'symbols' => $simbolo
                ]);

                $variazione = $response->json()['quoteResponse']['result'][0]['regularMarketPrice'];
            } else {
                $variazione = $coin['price'];
            }

            $delta = number_format((($variazione - $item->saldo) / $item->saldo) * 100, 2, ',', '.');

            if ((float)$delta > 60){
                \Mail::to('coltrida@gmail.com')->send(new WarningMail($item->platform->name, $delta));
                \Mail::to('coltricat75@gmail.com')->send(new WarningMail($trade->platform->name, $delta));
            }
        }
    }
}
