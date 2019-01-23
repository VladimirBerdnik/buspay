<?php

namespace App\Console\Commands;

use App\Domain\Import\ReplenishmentImporter;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Throwable;

/**
 * Console command to start replenishment from external storage import process.
 */
class ImportReplenishmentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avtotoleu:import_replenishment  {--D|days= : Import records, created given days count ago}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Performs import of replenishment from external storage';

    /**
     * External storage replenishment importer service.
     *
     * @var ReplenishmentImporter
     */
    private $replenishmentImporter;

    /**
     * Create a new command instance.
     *
     * @param ReplenishmentImporter $replenishmentImporter External storage replenishment importer service
     */
    public function __construct(ReplenishmentImporter $replenishmentImporter)
    {
        parent::__construct();
        $this->replenishmentImporter = $replenishmentImporter;
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

        $this->replenishmentImporter->import($date);
    }
}
