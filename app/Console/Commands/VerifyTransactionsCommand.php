<?php

namespace App\Console\Commands;

use App\Domain\Import\TransactionsVerifier;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Throwable;

/**
 * Console command that verifies transaction records in local and external storage.
 */
class VerifyTransactionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avtotoleu:verify_transactions  {--D|days= : Verify records, created given days count ago}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifies transaction records in local and external storage';

    /**
     * External storage transaction verifier service.
     *
     * @var TransactionsVerifier
     */
    private $transactionsVerifier;

    /**
     * Console command that verifies transaction records in local and external storage.
     *
     * @param TransactionsVerifier $transactionsVerifier External storage transaction verifier service
     */
    public function __construct(TransactionsVerifier $transactionsVerifier)
    {
        parent::__construct();
        $this->transactionsVerifier = $transactionsVerifier;
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

        $this->transactionsVerifier->start($date);
    }
}
