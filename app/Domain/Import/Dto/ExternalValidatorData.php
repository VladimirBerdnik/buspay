<?php

namespace App\Domain\Import\Dto;

use Saritasa\Dto;

/**
 * Validator details from external storage.
 *
 * @property-read integer $id Validator unique identifier in external storage
 * @property-read string $model Validator manufacturer or model
 * @property-read string $serial Validator serial number
 */
class ExternalValidatorData extends Dto
{
    public const ID = 'id';
    public const MODEL = 'model';
    public const SERIAL = 'serial';

    /**
     * Validator unique identifier in external storage.
     *
     * @var integer
     */
    protected $id;

    /**
     * Validator manufacturer or model.
     *
     * @var string
     */
    protected $model;

    /**
     * Validator serial number.
     *
     * @var string
     */
    protected $serial;
}
