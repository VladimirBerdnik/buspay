<?php

namespace App\Domain\Dto;

/**
 * Bus full details.
 *
 * @property-read integer $company_id Company identifier, to which this bus belongs
 * @property-read string $model_name Name of bus model
 * @property-read string $state_number Bus state number
 * @property-read boolean $active Does this bus works or not, can be assigned to route or not
 */
class BusFullData extends BusRouteData
{
    public const COMPANY_ID = 'company_id';
    public const MODEL_NAME = 'model_name';
    public const STATE_NUMBER = 'state_number';
    public const ACTIVE = 'active';

    /**
     * Company identifier, to which this bus belongs.
     *
     * @var integer
     */
    protected $company_id;

    /**
     * Name of bus model.
     *
     * @var string
     */
    protected $model_name;

    /**
     * Bus state number.
     *
     * @var string
     */
    protected $state_number;

    /**
     * Does this bus works or not, can be assigned to route or not.
     *
     * @var boolean
     */
    protected $active;
}
