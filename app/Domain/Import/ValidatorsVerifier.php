<?php

namespace App\Domain\Import;

use App\Domain\Import\Dto\ExternalValidatorData;
use App\Models\Validator;
use Throwable;

/**
 * Imported validators verifier. Allows to verify imported items in local and external storage.
 */
class ValidatorsVerifier extends ExternalEntitiesVerifierService
{
    /**
     * Class name of local items to verify.
     *
     * @var string
     */
    protected $localModelsClass = Validator::class;

    /**
     * Key name in local storage that is used to link local and remote records.
     *
     * @var string
     */
    protected $localItemsForeignKey = Validator::EXTERNAL_ID;

    /**
     * Primary key name of entities in external storage.
     *
     * @var string
     */
    protected $foreignItemsKey = ExternalValidatorData::ID;

    /**
     * Performs items verification in local and external storage.
     *
     * @throws Throwable
     */
    public function start(): void
    {
        $this->verify();
    }
}
