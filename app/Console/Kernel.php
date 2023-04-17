<?php

namespace App\Console;

use App\Console\Commands\BirthdayCelebration;
use App\Console\Commands\Reminder;
use App\Console\Commands\ReminderScheduleBefore24Hours;
use App\Console\Commands\ReminderScheduleBefore2Hours;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        BirthdayCelebration::class,
        ReminderScheduleBefore2Hours::class,
        ReminderScheduleBefore24Hours::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('segment:birthday')->dailyAt('12:00');
        $schedule->command('reminder:2hours')->everyFiveMinutes();
        $schedule->command('reminder:24hours')->everyFiveMinutes();
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
