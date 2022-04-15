<?php

namespace App\Jobs;

use App\Mail\WarningMail;
use App\Models\Trade;
use App\Services\TradeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @param TradeService $tradeService
     * @return void
     */
    public function handle()
    {
        $btcusdt = Http::get('https://api.binance.com/api/v3/ticker/price', [
            'symbol' => 'EURUSDT'
        ]);

        $trades = Trade::with('platform')->get();
        foreach ($trades as $trade){
            $delta = number_format((($trade->saldo - $btcusdt['price']) / $trade->saldo) * 100, 2, ',', '.');
            if ($delta < -4){
                \Mail::to('coltrida@gmail.com')->send(new WarningMail($trade->platform->name, $delta));
                \Mail::to('coltrida@gmail.com')->send(new WarningMail($trade->platform->name, $delta));
            }
        }
       // \Mail::to('coltrida@gmail.com')->send(new WarningMail('pippo', $btcusdt['price']));
    }
}
