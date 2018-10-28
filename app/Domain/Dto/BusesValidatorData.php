<?php

namespace App\Domain\Dto;

use Saritasa\Dto;

/**
 * Bus to Validator assignment details.
 *
 * @property-read integer $bus_id Linked to validator bus identifier
 * @property-read integer $validator_id Linked to bus validator identifier
 * @property-read string $active_from Start date of activity period of this record
 * @property-read string|null $active_to End date of activity period of this record
 */
class BusesValidatorData extends Dto
{
    public const BUS_ID = 'bus_id';
    public const VALIDATOR_ID = 'validator_id';
    public const ACTIVE_FROM = 'active_from';
    public const ACTIVE_TO = 'active_to';

    /**
     * Linked to validator bus identifier.
     *
     * @var integer
     */
    protected $bus_id;

    /**
     * Linked to bus validator identifier.
     *
     * @var integer
     */
    protected $validator_id;

    /**
     * Start date of activity period of this record.
     *
     * @var string
     */
    protected $active_from;

    /**
     * End date of activity period of this record.
     *
     * @var string|null
     */
    protected $active_to;
}
