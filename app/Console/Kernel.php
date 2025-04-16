<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\calculateDailyScore;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('passport:purge')->daily(); // Run daily to remove expired tokens from table
        $schedule->command('telescope:prune')->daily();
        
        // Schedule the cleanup refresh tokens command to run daily
        //$schedule->command('passport:cleanup-refresh-tokens')->daily();

        $schedule->command('deposit:CheckDeposit start')->everyTwoMinutes(); //TODO - Used for Automatic deposit processing - needs to be checked to be sure it is correct
        $schedule->command('update:UsdtPrice start')->everyTwoMinutes();
        $schedule->command('check:SmsCredit start')->everyTwoHours();
        $schedule->command('coinex:CheckCredit start')->everyTwoHours(); // check coinex credit
        $schedule->command('coinex:ColdWallet start')->hourly(); //TODO  - needs to be checked 
        //$schedule->command('calculate:btzPrice')->daily();  //TODO - Wrong
        $schedule->command('update:CoinexAssets start')->daily(); //update asset icons url from coinapi 
        //$schedule->command('calculate:daily-score')->daily();
        $schedule->command('update:dailyBtzProfit start')->daily();
    }

    protected function shortSchedule(\Spatie\ShortSchedule\ShortSchedule $shortSchedule)
    {

        //$shortSchedule->command('coinex:CoinexServer start')->everySeconds(5);
        $shortSchedule->command('coinex:ExtractCoinexMarketInfo start')->everySeconds(450);
        $shortSchedule->command('coinex:ExtractCurrencyInfo start')->everySeconds(450);
        //$shortSchedule->command('bitazar:Trader start')->everySeconds(450); //for virtual trading not used for now
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
