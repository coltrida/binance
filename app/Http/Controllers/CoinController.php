<?php

namespace App\Http\Controllers;

use App\Models\Coin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        $coinEsistente = Coin::where('ticker', $request->ticker)->first();

        if ($coinEsistente) {

        } else{
            Coin::create([
                'acquisizione' => $request->acquisizione,
                'ticker' => $request->ticker,
                'prezzoAcquisto' => (float)$importo,
                'quantita' => $request->quantita
            ]);
        }

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
}
