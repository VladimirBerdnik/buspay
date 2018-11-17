<?php

namespace App\Domain\Dto;

use Saritasa\Dto;

/**
 * Route Sheet details.
 *
 * @property-read integer $company_id Company identifier to which this route sheet belongs to
 * @property-read integer|null $route_id Bus route identifier, which served the driver on the bus
 * @property-read integer $bus_id Bus identifier that is on route
 * @property-read integer|null $driver_id Driver identifier that is on bus on route
 * @property-read string $active_from Start date of activity period of this record
 * @property-read string|null $active_to End date of activity period of this record
 */
class RouteSheetData extends Dto
{
    public const COMPANY_ID = 'company_id';
    public const ROUTE_ID = 'route_id';
    public const BUS_ID = 'bus_id';
    public const DRIVER_ID = 'driver_id';
    public const ACTIVE_FROM = 'active_from';
    public const ACTIVE_TO = 'active_to';

    /**
     * Bus route identifier, which served the driver on the bus.
     *
     * @var integer|null
     */
    protected $route_id;

    /**
     * Company identifier to which this route sheet belongs to.
     *
     * @var integer
     */
    protected $company_id;

    /**
     * Bus identifier that is on route.
     *
     * @var integer
     */
    protected $bus_id;

    /**
     * Driver identifier that is on bus on route.
     *
     * @var integer|null
     */
    protected $driver_id;

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
