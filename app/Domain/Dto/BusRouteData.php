<?php

namespace App\Domain\Dto;

/**
 * Bus route details.
 *
 * @property-read integer|null $route_id Usual route identifier, on which this bus is
 */
class BusRouteData extends BusData
{
    public const ROUTE_ID = 'route_id';

    /**
     * Usual route identifier, on which this bus is.
     *
     * @var integer|null
     */
    protected $route_id;
}
