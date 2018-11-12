<?php

namespace App\Domain\Exceptions\Integrity;

use App\Models\Validator;

/**
 * Thrown when bus to validator assignment not found, but expected.
 */
class NoBusForValidatorException extends BusinessLogicIntegrityException
{
    /**
     * Validator for which bus not found.
     *
     * @var Validator
     */
    private $validator;

    /**
     * Thrown when bus to validator assignment not found, but expected.
     *
     * @param Validator $validator Validator for which bus not found
     */
    public function __construct(Validator $validator)
    {
        parent::__construct('No bus to validator historical assignment found when expected');
        $this->validator = $validator;
    }

    /**
     * Validator for which bus not found.
     *
     * @return Validator
     */
    public function getValidator(): Validator
    {
        return $this->validator;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $validator = $this->getValidator();

        return "No validator [{$validator->id}] to bus [{$validator->bus_id}] historical assignment found but expected";
    }
}
