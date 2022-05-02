<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use App\Models\Trade;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $trades = Trade::nonaperture()->with('platform')->orderBy('iscrizione', 'DESC')->paginate(10);
        $totInvestimento = $trades->sum('import');
        $platforms = Platform::nonaperture()->orderBy('name')->get();


        $btcusdt = Http::get('https://api.binance.com/api/v3/ticker/price', [
            'symbol' => 'EURUSDT'
        ]);

        return view('trades', compact('trades', 'platforms', 'btcusdt', 'totInvestimento'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function aperture()
    {
        $variazioni = [];

        $trades = Trade::aperture()->with('platform')->orderBy('iscrizione', 'DESC')->paginate(10);
        $totInvestimento = $trades->sum('import');
        $platforms = Platform::aperture()->orderBy('name')->get();

        foreach ($trades as $item){

            $simbolo = Str::substr($item->platform->name, 11);

            $variazioni[$item->id] = Http::get('https://api.binance.com/api/v3/ticker/price', [
                'symbol' => $simbolo.'USDT'
            ]);

            if(!isset($variazioni[$item->id]['price'])){

                $response = Http::withHeaders([
                    'X-RapidAPI-Host' => 'apidojo-yahoo-finance-v1.p.rapidapi.com',
                    'X-RapidAPI-Key' => '6d9727b350msh6d5d731383e0c40p166b61jsn7705b35f552b'
                ])->get('https://apidojo-yahoo-finance-v1.p.rapidapi.com/market/v2/get-quotes', [
                    'region' => 'US',
                    'symbols' => $simbolo
                ]);

                $variazioni[$item->id] = $response->json()['quoteResponse']['result'][0];
               // dd($response->json()['quoteResponse']['result'][0]['regularMarketPrice']);
            }
        }
//dd($variazioni[4]['regularMarketPrice']);

        return view('aperture', compact('trades', 'platforms', 'variazioni', 'totInvestimento'));
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
        Trade::create($request->all());
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function apertureStore(Request $request)
    {
        $nomePiattaforma = Platform::find($request->platform_id)->name;
        $simbolo = Str::substr($nomePiattaforma, 11);

        $ticker = Http::get('https://api.binance.com/api/v3/ticker/price', [
            'symbol' => $simbolo.'USDT'
        ]);

        if(!isset($ticker['price'])){
            //dd($simbolo);

            $response = Http::withHeaders([
                'X-RapidAPI-Host' => 'apidojo-yahoo-finance-v1.p.rapidapi.com',
                'X-RapidAPI-Key' => '6d9727b350msh6d5d731383e0c40p166b61jsn7705b35f552b'
            ])->get('https://apidojo-yahoo-finance-v1.p.rapidapi.com/market/v2/get-quotes', [
                'region' => 'US',
                'symbols' => $simbolo
            ]);
            $request['saldo'] = $response->json()['quoteResponse']['result'][0]['regularMarketPrice'];

        } else {
            $request['saldo'] = $ticker['price'];
        }


        Trade::create($request->all());
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
