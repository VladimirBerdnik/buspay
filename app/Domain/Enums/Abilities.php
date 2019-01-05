<?php

namespace App\Domain\Enums;

use Saritasa\Enum;

/**
 * Available application intentions that can be performed with application entities.
 */
class Abilities extends Enum
{
    public const SHOW = 'show';
    public const GET = 'get';
    public const CREATE = 'create';
    public const UPDATE = 'update';
    public const DELETE = 'delete';
    public const CHANGE_BUS_ROUTE = 'changeBusRoute';
    public const CHANGE_DRIVER_BUS = 'changeDriverBus';
    public const GET_DRIVERS_CARDS = 'getDriversCards';
}
