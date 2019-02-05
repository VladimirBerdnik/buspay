<?php

namespace App\Domain\Enums;

use Saritasa\Enum;

/**
 * Available application entities types on which actions are allowed.
 */
class EntitiesTypes extends Enum
{
    public const BUS = 'bus';
    public const CARD_TYPE = 'cardType';
    public const CARD = 'card';
    public const COMPANY = 'company';
    public const DRIVER = 'driver';
    public const PROFILE = 'profile';
    public const REPLENISHMENT = 'replenishment';
    public const ROLE = 'role';
    public const ROUTE_SHEET = 'routeSheet';
    public const ROUTE = 'route';
    public const TARIFF = 'tariff';
    public const TARIFF_PERIOD = 'tariffPeriod';
    public const TRANSACTION = 'transaction';
    public const USER = 'user';
    public const VALIDATOR = 'validator';
}
