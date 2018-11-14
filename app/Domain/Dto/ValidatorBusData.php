<?php

namespace App\Domain\Dto;

use Saritasa\Dto;

/**
 * Validator bus details. Used to assign bus to validator.
 *
 * @property-read integer|null $bus_id Bus identifier to assign
 */
class ValidatorBusData extends Dto
{
    public const BUS_ID = 'bus_id';

    /**
     * Current bus identifier where validator located.
     *
     * @var integer|null
     */
    protected $bus_id;
}
