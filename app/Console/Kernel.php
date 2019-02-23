<?php

namespace App\Console;

use App\Console\Commands\CloseRouteSheets;
use App\Console\Commands\ImportCardsCommand;
use App\Console\Commands\ImportReplenishmentsCommand;
use App\Console\Commands\ImportTransactionsCommand;
use App\Console\Commands\ImportValidatorsCommand;
use App\Console\Commands\ProcessUnassignedTransactions;
use App\Console\Commands\VerifyReplenishmentsCommand;
use App\Console\Commands\VerifyTransactionsCommand;
use App\Console\Commands\VerifyValidatorsCommand;
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
        VerifyReplenishmentsCommand::class,
        VerifyTransactionsCommand::class,
        VerifyValidatorsCommand::class,
        ProcessUnassignedTransactions::class,
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
        // Import entities
        $schedule->command(ImportReplenishmentsCommand::class, ['--days' => 1])
            ->hourly()
            ->withoutOverlapping()
            ->onOneServer();
        $schedule->command(ImportTransactionsCommand::class, ['--days' => 1])
            ->hourly()
            ->withoutOverlapping()
            ->onOneServer();
        $schedule->command(ImportCardsCommand::class, ['--days' => 1])
            ->hourly()
            ->withoutOverlapping()
            ->onOneServer();
        $schedule->command(ImportValidatorsCommand::class)
            ->twiceDaily(6, 12)
            ->withoutOverlapping()
            ->onOneServer();

        // Once per day verify previously imported records
        $schedule->command(VerifyReplenishmentsCommand::class)->dailyAt('02:00');
        $schedule->command(VerifyTransactionsCommand::class)->dailyAt('02:00');
        $schedule->command(VerifyValidatorsCommand::class)->dailyAt('02:00');

        // Close all opened route sheets
        $schedule->command(CloseRouteSheets::class)
            ->dailyAt(config('buspay.driver.shift_cancel_hour'))
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
