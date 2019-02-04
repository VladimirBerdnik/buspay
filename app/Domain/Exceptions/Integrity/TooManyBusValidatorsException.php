<?php

namespace App\Domain\Exceptions\Integrity;

use App\Models\BusesValidator;
use App\Models\Validator;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Thrown when multiple bus to validator assignments for date are exists.
 */
class TooManyBusValidatorsException extends BusinessLogicIntegrityException
{
    /**
     * Date for which many bus to validator assignments exists.
     *
     * @var Carbon
     */
    protected $date;

    /**
     * List of bus to validator assignments for date.
     *
     * @var Collection|BusesValidator[]
     */
    protected $busValidators;

    /**
     * Validator for which few assignments exists.
     *
     * @var Validator
     */
    protected $validator;

    /**
     * Thrown when multiple bus to validator assignments for date are exists.
     *
     * @param Carbon $date Date for which many bus to validator assignments exists
     * @param Validator $validator Validator for which few assignments exists
     * @param Collection|BusesValidator[] $busValidators List of bus to validator assignments for date
     */
    public function __construct(Carbon $date, Validator $validator, Collection $busValidators)
    {
        parent::__construct('Few bus to validator assignments for date');
        $this->date = $date;
        $this->busValidators = $busValidators;
        $this->validator = $validator;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $periodsIdentifiers = $this->busValidators->pluck(BusesValidator::ID)->toArray();
        $date = $this->date->toIso8601String();

        return "For date {$date} few buses to validator [{$this->validator->id}] " .
            "assignments exists: " . implode(', ', $periodsIdentifiers);
    }
}
