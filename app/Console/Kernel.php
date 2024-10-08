<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel.
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\SendNotifications'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('send:notifications')->cron('03 * * * *');
//        $schedule->command('send-noti:data')->everyFiveMinutes();
//        $schedule->command('send-noti:competitor')->everyFiveMinutes();

         $schedule->command('send-noti:data')->cron('*/2 * * * *');
         $schedule->command('send-noti:competitor')->cron('*/2 * * * *');
//         $schedule->command('send:notifications')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
