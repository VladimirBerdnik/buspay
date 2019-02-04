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
    protected $validator;

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
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $validator = $this->validator;

        return "No validator [{$validator->id}] to bus [{$validator->bus_id}] historical assignment found but expected";
    }
}
