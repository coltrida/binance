<?php


namespace App\Services;
use Illuminate\Support\Facades\Http;

class TradeService
{
    public function btcusdt()
    {
        return Http::get('https://api.binance.com/api/v3/ticker/price', [
            'symbol' => 'EURUSDT'
        ]);
    }
}
