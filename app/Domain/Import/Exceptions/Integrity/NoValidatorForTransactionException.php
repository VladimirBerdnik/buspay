<?php

namespace App\Domain\Import\Exceptions\Integrity;

/**
 * Thrown when authorized card validator not found during import transactions attempt.
 */
class NoValidatorForTransactionException extends BusinessLogicIntegrityImportException
{
    /**
     * Serial number of validator that wasn't found.
     *
     * @var string
     */
    private $validatorSerial;

    /**
     * Thrown when authorized card validator not found during import transactions attempt.
     *
     * @param string $validatorSerial Serial number of validator that wasn't found
     */
    public function __construct(string $validatorSerial)
    {
        parent::__construct('No validators by serial number found');
        $this->validatorSerial = $validatorSerial;
    }

    /**
     * Serial number of validator that wasn't found.
     *
     * @return string
     */
    public function getValidatorSerial(): string
    {
        return $this->validatorSerial;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        return "No validators with serial number [{$this->getValidatorSerial()}] found";
    }
}
