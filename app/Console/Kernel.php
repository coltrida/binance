<?php

namespace App\Console;

use App\Mail\WarningMail;
use App\Models\Trade;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $btcusdt = Http::get('https://api.binance.com/api/v3/ticker/price', [
                'symbol' => 'EURUSDT'
            ]);
            $trades = Trade::with('platform')->get();
            foreach ($trades as $trade){
                $delta = number_format((($trade->saldo - $btcusdt['price']) / $trade->saldo) * 100, 2, ',', '.');
                \Mail::to('coltrida@gmail.com')->send(new WarningMail($trade->platform->name, $delta));
                if ($delta < 5){
                    \Mail::to('coltrida@gmail.com')->send(new WarningMail($delta));
                }
            }
        })->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
