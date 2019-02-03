<?php

namespace App\Console;

use App\Console\Commands\CloseRouteSheets;
use App\Console\Commands\ImportCardsCommand;
use App\Console\Commands\ImportReplenishmentsCommand;
use App\Console\Commands\ImportTransactionsCommand;
use App\Console\Commands\ImportValidatorsCommand;
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
    protected $commands = [
        ImportCardsCommand::class,
        ImportValidatorsCommand::class,
        CloseRouteSheets::class,
        ImportReplenishmentsCommand::class,
        ImportTransactionsCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  Schedule $schedule Schedule instance
     *
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(ImportReplenishmentsCommand::class)
            ->hourly()
            ->withoutOverlapping()
            ->onOneServer();
        $schedule->command(ImportTransactionsCommand::class)
            ->hourly()
            ->withoutOverlapping()
            ->onOneServer();
        $schedule->command(ImportCardsCommand::class)
            ->hourly()
            ->withoutOverlapping()
            ->onOneServer();
        $schedule->command(ImportValidatorsCommand::class)
            ->hourly()
            ->withoutOverlapping()
            ->onOneServer();
        $schedule->command(CloseRouteSheets::class)
            ->dailyAt(config('buspay.driver.shift_cancel_hour'))
            ->withoutOverlapping()
            ->onOneServer();
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
