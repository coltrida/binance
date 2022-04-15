<?php

namespace App\Jobs;

use App\Mail\WarningMail;
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
     * @return void
     */
    public function handle()
    {
        $btcusdt = Http::get('https://api.binance.com/api/v3/ticker/price', [
            'symbol' => 'EURUSDT'
        ]);
        \Mail::to('coltrida@gmail.com')->send(new WarningMail('pippo', $btcusdt['price']));
    }
}
