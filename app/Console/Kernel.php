<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Log;
use App\Http\Controllers\RatingController as Rating;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function(){          
            $now = Date('Y-m-d H:i:s');
            Log::info("NOW ".$now);
            $unjudgedContests = DB::table('contests')->where([['Judged',0],['ContestEnd','<',$now]])->get();
            foreach($unjudgedContests as $key=>$contest){
                $req = new Request();
                $req->ContestID = $contest->ContestID;
                Log::info("RATED ".$req->ContestID);
                Rating::rateContest($req);
                DB::table('contests')->where('ContestID',$contest->ContestID)->update(['Judged'=>1]);
            }
        })->everyMinute();
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
