<?php

namespace App\Console\Commands;

use App\Domain\Import\ValidatorsVerifier;
use Illuminate\Console\Command;
use Throwable;

/**
 * Console command that verifies validator records in local and external storage.
 */
class VerifyValidatorsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avtotoleu:verify_validators';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifies validator records in local and external storage';

    /**
     * External storage validator verifier service.
     *
     * @var ValidatorsVerifier
     */
    private $validatorsVerifier;

    /**
     * Console command that verifies validator records in local and external storage.
     *
     * @param ValidatorsVerifier $validatorsVerifier External storage validator verifier service
     */
    public function __construct(ValidatorsVerifier $validatorsVerifier)
    {
        parent::__construct();
        $this->validatorsVerifier = $validatorsVerifier;
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
        $this->validatorsVerifier->start();
    }
}
