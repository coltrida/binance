<?php

namespace App\Http\Controllers;

use App\Models\Coin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CoinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $variazioni = [];
        $totalePortafoglio = 0;
        $totaleOriginale = 0;
        $coins = Coin::orderBy('ticker')->get();

        foreach ($coins as $item){
            $variazioni[$item->id] = Http::get('https://api.binance.com/api/v3/ticker/price', [
                'symbol' => $item->ticker.'USDT'
            ]);
            $totalePortafoglio += (float)$item->quantita * (float)$variazioni[$item->id]['price'];
            $totaleOriginale += (float)$item->quantita * (float)$item->prezzoAcquisto;
        }
        $coins = Coin::orderBy('ticker')->paginate(10);
        return view('coins', compact('coins', 'variazioni', 'totalePortafoglio', 'totaleOriginale'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $importo = str_replace(',', '.', $request->prezzoAcquisto);

        /*$coinEsistente = Coin::where('ticker', $request->ticker)->first();

        if ($coinEsistente) {

        } else{
            Coin::create([
                'acquisizione' => $request->acquisizione,
                'ticker' => $request->ticker,
                'prezzoAcquisto' => (float)$importo,
                'quantita' => $request->quantita
            ]);
        }*/

        Coin::create([
            'acquisizione' => $request->acquisizione,
            'ticker' => $request->ticker,
            'prezzoAcquisto' => (float)$importo,
            'quantita' => $request->quantita
        ]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Coin $coin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Coin $coin)
    {
        $coin->delete();
        return redirect()->back();
    }

    public function valutazioni()
    {
        return view('valutazioni');
    }

    public function eseguiValutazioni(Request $request)
    {
        // carico le coins
        $coins = [];

        $percentualeCorretta = Str::replace(',', '.', $request->percentuale);
        $percentuale = (float) $percentualeCorretta / 100;

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
}
