<?php

namespace App\Domain\Dto;

use Saritasa\Dto;

/**
 * Bus details.
 *
 * @property-read integer $company_id Company identifier, to which this bus belongs
 * @property-read integer|null $route_id Usual route identifier, on which this bus is
 * @property-read string $model_name Name of bus model
 * @property-read string $state_number Bus state number
 * @property-read boolean $active Does this bus works or not, can be assigned to route or not
 */
class BusData extends Dto
{
    public const COMPANY_ID = 'company_id';
    public const ROUTE_ID = 'route_id';
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
     * Usual route identifier, on which this bus is.
     *
     * @var integer|null
     */
    protected $route_id;

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
