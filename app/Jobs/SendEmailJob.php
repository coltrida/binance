<?php

namespace App\Jobs;

use App\Mail\WarningMail;
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
    public function handle(TradeService $tradeService)
    {
       // $btcusdt = $tradeService->btcusdt();
        \Mail::to('coltrida@gmail.com')->send(new WarningMail('pippo', 5));
    }
}
