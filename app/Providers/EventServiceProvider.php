<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\DB;
use Log;

/**
 * Registers listeners for specific events fired in application.
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var string[][]
     */
    protected $listen = [];

    /**
     * Contains information about executed queries and executions count.
     *
     * @var integer[]
     */
    private $requestsExecutions = [];

    /** {@inheritdoc} */
    public function register(): void
    {
        parent::register();

        DB::listen(function ($query): void {
            $this->requestsExecutions[$query->sql] = ($this->requestsExecutions[$query->sql] ?? 0) + 1;
        });

        register_shutdown_function(function (): void {
            foreach ($this->requestsExecutions as $query => $count) {
                switch (true) {
                    case $count < 3:
                        $severity = null;
                        break;
                    case $count === 3:
                        $severity = 'debug';
                        break;
                    case $count <= 5:
                        $severity = 'notice';
                        break;
                    default:
                        $severity = 'warning';
                        break;
                }

                if ($severity) {
                    Log::{$severity}("SQL query was executed {$count} time(s)", [$query, $_SERVER['REQUEST_URI']]);
                }
            }
        });
    }
}
