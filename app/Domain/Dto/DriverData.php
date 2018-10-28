<?php

namespace App\Domain\Dto;

use Saritasa\Dto;

/**
 * Driver details.
 *
 * @property-read integer $company_id Company identifier in which this driver works
 * @property-read integer|null $bus_id Bus identifier, on which this driver usually works
 * @property-read integer|null $card_id Current driver card identifier
 * @property-read string $full_name Driver full name
 * @property-read boolean $active Does this driver works or not, can be assigned to route or not
 */
class DriverData extends Dto
{
    public const COMPANY_ID = 'company_id';
    public const BUS_ID = 'bus_id';
    public const CARD_ID = 'card_id';
    public const FULL_NAME = 'full_name';
    public const ACTIVE = 'active';

    /**
     * Company identifier in which this driver works.
     *
     * @var integer
     */
    protected $company_id;

    /**
     * Bus identifier, on which this driver usually works.
     *
     * @var integer|null
     */
    protected $bus_id;

    /**
     * Current driver card identifier.
     *
     * @var integer|null
     */
    protected $card_id;

    /**
     * Driver full name.
     *
     * @var string
     */
    protected $full_name;

    /**
     * Does this driver works or not, can be assigned to route or not.
     *
     * @var boolean
     */
    protected $active;
}
