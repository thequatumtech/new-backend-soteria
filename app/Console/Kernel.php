<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\NotifyRenewals;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected $commands = [
        NotifyRenewals::class,
    ];
    protected function schedule(Schedule $schedule)
    {
        //  $schedule->call(function () {
        // Mail::raw('Cron scheduler is working at ' . now(), function ($message) {
        //         $message->to('premvadhavana@gmail.com')
        //                 ->subject('Cron Test Email');
        //     });
        //     \Log::info("Test email sent at " . now());
        // })->everyMinute(); // run every minute for testing

          \Log::info("Cron scheduler triggered at: " . now());
        // $schedule->command('inspire')->hourly();
        //         $schedule->command('send:coupons')->everyFiveMinutes();
        // $schedule->command('notify:renewals')->dailyAt('00:15');
        // $schedule->command('policies:reset-renewed')->dailyAt('00:00');
        $schedule->command('policies:reset-renewed')->everyMinute();
        $schedule->command('notify:renewals')->everyMinute();
        $schedule->command('queue:work --queue=default --sleep=3 --tries=3 --timeout=90')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()

    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
