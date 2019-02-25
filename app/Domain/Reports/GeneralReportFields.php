<?php

namespace App\Domain\Reports;

use Saritasa\Enum;

/**
 * Available general report fields that can be requested.
 */
class GeneralReportFields extends Enum
{
    public const COMPANY = 'company';
    public const ROUTE = 'route';
    public const TARIFF = 'tariff';
    public const BUS = 'bus';
    public const DRIVER = 'driver';
    public const VALIDATOR = 'validator';
    public const CARD_TYPE = 'cardType';
    public const DATE = 'date';
    public const HOUR = 'hour';
}
