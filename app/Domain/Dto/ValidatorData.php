<?php

namespace App\Domain\Dto;

use Saritasa\Dto;

/**
 * Validator details.
 *
 * @property-read integer|null $bus_id Current bus identifier where validator located
 * @property-read string $serial_number Validator serial number
 * @property-read string $model Validator manufacturer or model
 */
class ValidatorData extends Dto
{
    public const BUS_ID = 'bus_id';
    public const SERIAL_NUMBER = 'serial_number';
    public const MODEL = 'model';

    /**
     * Current bus identifier where validator located.
     *
     * @var integer|null
     */
    protected $bus_id;

    /**
     * Validator serial number.
     *
     * @var string
     */
    protected $serial_number;

    /**
     * Validator manufacturer or model.
     *
     * @var string
     */
    protected $model;
}
