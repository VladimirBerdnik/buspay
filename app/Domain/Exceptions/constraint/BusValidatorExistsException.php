<?php

namespace App\Domain\Exceptions\Constraint;

use App\Extensions\ActivityPeriod\IActivityPeriod;
use App\Models\BusesValidator;

/**
 * Thrown when bus to validator assignment already exists.
 */
class BusValidatorExistsException extends BusinessLogicConstraintException
{
    /**
     * Validator to bus assignment for which activity period exists.
     *
     * @var BusesValidator
     */
    private $busesValidator;

    /**
     * Thrown when bus to validator assignment already exists.
     *
     * @param BusesValidator|IActivityPeriod $busesValidator Validator to bus assignment for which activity period
     *     exists
     */
    public function __construct(BusesValidator $busesValidator)
    {
        parent::__construct('Bus activity period already exists exception');
        $this->busesValidator = $busesValidator;
    }

    /**
     * Validator to bus assignment for which activity period exists.
     *
     * @return BusesValidator
     */
    public function getBusesValidator(): BusesValidator
    {
        return $this->busesValidator;
    }
}
