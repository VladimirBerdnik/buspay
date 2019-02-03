<?php

namespace App\Console\Commands;

use App\Domain\Import\TransactionsImporter;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Throwable;

/**
 * Console command to start transaction from external storage import process.
 */
class ImportTransactionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avtotoleu:import_transactions  {--D|days= : Import records, created given days count ago}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Performs import of transactions from external storage';

    /**
     * External storage transactions importer service.
     *
     * @var TransactionsImporter
     */
    private $transactionsImporter;

    /**
     * Create a new command instance.
     *
     * @param TransactionsImporter $transactionImporter External storage transactions importer service
     */
    public function __construct(TransactionsImporter $transactionImporter)
    {
        parent::__construct();
        $this->transactionsImporter = $transactionImporter;
    }

    /**
     * Execute the console command.
     *
     * @return void
     *
     * @throws Throwable
     */
    public function handle(): void
    {
        $daysCount = $this->option('days');

        $date = $daysCount ? Carbon::now()->subDay($daysCount) : null;

        $this->transactionsImporter->import($date);
    }
}
