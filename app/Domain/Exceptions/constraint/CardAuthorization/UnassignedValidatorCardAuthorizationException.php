<?php

namespace App\Domain\Exceptions\Constraint\CardAuthorization;

use App\Models\Validator;
use Carbon\Carbon;

/**
 * Thrown when authorization on unassigned to bus validator detected.
 */
class UnassignedValidatorCardAuthorizationException extends CardAuthorizationException
{
    /**
     * Validator on which authorization was detected.
     *
     * @var Validator
     */
    protected $validator;

    /**
     * Date at which authorization was detected.
     *
     * @var Carbon
     */
    protected $date;

    /**
     * Thrown when authorization on unassigned to bus validator detected.
     *
     * @param Validator $validator Validator on which authorization was detected
     * @param Carbon $date Date at which authorization was detected
     */
    public function __construct(Validator $validator, Carbon $date)
    {
        parent::__construct('Authorization on unassigned validator');
        $this->validator = $validator;
        $this->date = $date;
    }
}
