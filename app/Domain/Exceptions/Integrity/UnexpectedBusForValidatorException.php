<?php

namespace App\Domain\Exceptions\Integrity;

use App\Models\Bus;
use App\Models\BusesValidator;

/**
 * Thrown when bus to validator assignment has unexpected bus value.
 */
class UnexpectedBusForValidatorException extends BusinessLogicIntegrityException
{
    /**
     * Bus to validator assignment where unexpected bus found.
     *
     * @var BusesValidator
     */
    private $busesValidator;

    /**
     * Expected bus.
     *
     * @var Bus
     */
    private $bus;

    /**
     * Thrown when bus to validator assignment has unexpected bus value.
     *
     * @param BusesValidator $busesValidator Bus to validator assignment where unexpected bus found
     * @param Bus $bus Expected bus
     */
    public function __construct(BusesValidator $busesValidator, Bus $bus)
    {
        parent::__construct('Unexpected bus in bus to validator historical assignment');
        $this->busesValidator = $busesValidator;
        $this->bus = $bus;
    }

    /**
     * Bus to validator assignment where unexpected bus found.
     *
     * @return BusesValidator
     */
    public function getBusesValidator(): BusesValidator
    {
        return $this->busesValidator;
    }

    /**
     * Expected bus.
     *
     * @return Bus
     */
    public function getBus(): Bus
    {
        return $this->bus;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $busesValidator = $this->getBusesValidator();

        return "Unexpected bus [{$busesValidator->bus_id}] for validator [{$busesValidator->validator_id}] found " .
            "in bus to validator assignment [{$busesValidator->id}]. Expected bus is [{$this->getBus()->id}]";
    }
}
