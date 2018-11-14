<?php

namespace App\Console\Commands;

use App\Domain\Import\ValidatorsImporter;
use Illuminate\Console\Command;
use Throwable;

/**
 * Console command to start validators from external storage import process.
 */
class ImportValidatorsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avtotoleu:import_validators';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Performs import of validators from external storage';

    /**
     * External storage validators importer service.
     *
     * @var ValidatorsImporter
     */
    private $validatorsImporter;

    /**
     * Create a new command instance.
     *
     * @param ValidatorsImporter $validatorsImporter External storage validators importer service
     */
    public function __construct(ValidatorsImporter $validatorsImporter)
    {
        parent::__construct();
        $this->validatorsImporter = $validatorsImporter;
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
        $this->validatorsImporter->import();
    }
}
