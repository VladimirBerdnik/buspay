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
    private $date;

    /**
     * List of bus to validator assignments for date.
     *
     * @var Collection|BusesValidator[]
     */
    private $busValidators;

    /**
     * Validator for which few assignments exists.
     *
     * @var Validator
     */
    private $validator;

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
     * Returns Date for which many bus to validator assignments exists.
     *
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * Validator for which few assignments exists.
     *
     * @return Validator
     */
    public function getValidator(): Validator
    {
        return $this->validator;
    }

    /**
     * Returns List of bus to validator assignments for date.
     *
     * @return Collection|BusesValidator[]
     */
    public function getBusValidators()
    {
        return $this->busValidators;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $periodsIdentifiers = $this->getBusValidators()->pluck(BusesValidator::ID)->toArray();
        $date = $this->getDate()->toIso8601String();

        return "For date {$date} few buses to validator [{$this->getValidator()->id}] " .
            "assignments exists: " . implode(', ', $periodsIdentifiers);
    }
}
