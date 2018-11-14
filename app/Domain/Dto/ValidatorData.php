<?php

namespace App\Domain\Dto;

use Saritasa\Dto;

/**
 * Validator details. Used to create or update validator parameters.
 *
 * @property-read string $serial_number Validator serial number
 * @property-read string $model Validator manufacturer or model
 * @property-read integer $external_id External storage record identifier
 */
class ValidatorData extends Dto
{
    public const SERIAL_NUMBER = 'serial_number';
    public const MODEL = 'model';
    public const EXTERNAL_ID = 'external_id';

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

    /**
     * External storage record identifier.
     *
     * @var integer
     */
    protected $external_id;
}
