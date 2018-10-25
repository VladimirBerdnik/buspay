<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Application console kernel to manage console commands and schedules.
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var string[]
     */
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param  Schedule $schedule Schedule instance
     *
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        require base_path('routes/console.php');
    }
}
