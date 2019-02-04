<?php

namespace App\Console\Commands;

use App\Domain\Import\ReplenishmentsVerifier;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Throwable;

/**
 * Console command that verifies replenishment records in local and external storage.
 */
class VerifyReplenishmentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avtotoleu:verify_replenishment  {--D|days= : Verify records, created given days count ago}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifies replenishment records in local and external storage';

    /**
     * External storage replenishment verifier service.
     *
     * @var ReplenishmentsVerifier
     */
    private $replenishmentsVerifier;

    /**
     * Console command that verifies replenishment records in local and external storage.
     *
     * @param ReplenishmentsVerifier $replenishmentsVerifier External storage replenishment verifier service
     */
    public function __construct(ReplenishmentsVerifier $replenishmentsVerifier)
    {
        parent::__construct();
        $this->replenishmentsVerifier = $replenishmentsVerifier;
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

        $this->replenishmentsVerifier->start($date);
    }
}
