<?php

namespace App\Domain\Dto;

/**
 * Driver bus details.
 *
 * @property-read integer|null $bus_id Bus identifier, on which this driver usually works
 */
class DriverBusData extends DriverData
{
    public const BUS_ID = 'bus_id';

    /**
     * Bus identifier, on which this driver usually works.
     *
     * @var integer|null
     */
    protected $bus_id;
}
