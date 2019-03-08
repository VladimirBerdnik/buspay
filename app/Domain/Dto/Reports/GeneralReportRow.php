<?php

namespace App\Domain\Dto\Reports;

use Illuminate\Contracts\Support\Arrayable;
use Saritasa\Dto;

/**
 * Data of general report row.
 *
 * @property-read string|null $company Company name
 * @property-read string|null $route Route name
 * @property-read string|null $tariff Tariff name
 * @property-read string|null $bus Bus state number
 * @property-read string|null $driver Driver full nam
 * @property-read string|null $validator Validator serial number
 * @property-read string|null $cardType Card type name
 * @property-read string|null $date Date of report row
 * @property-read string|null $hour Hour of report row
 * @property-read number|null $count Transactions count within given report group
 * @property-read number|null $sum Transactions sum within given report group
 */
class GeneralReportRow extends Dto implements Arrayable
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
    public const COUNT = 'count';
    public const SUM = 'sum';

    /**
     * Company name.
     *
     * @var string|null
     */
    protected $company;

    /**
     * Route name.
     *
     * @var string|null
     */
    protected $route;

    /**
     * Tariff name.
     *
     * @var string|null
     */
    protected $tariff;

    /**
     * Bus state number.
     *
     * @var string|null
     */
    protected $bus;

    /**
     * Driver full name
     *
     * @var string|null
     */
    protected $driver;

    /**
     * Validator serial number.
     *
     * @var string|null
     */
    protected $validator;

    /**
     * Card type name.
     *
     * @var string|null
     */
    protected $cardType;

    /**
     * Date of report row.
     *
     * @var string|null
     */
    protected $date;

    /**
     * Hour of report row.
     *
     * @var string|null
     */
    protected $hour;

    /**
     * Transactions count within given report group.
     *
     * @var number|null
     */
    protected $count;

    /**
     * Transactions sum within given report group.
     *
     * @var number|null
     */
    protected $sum;
}
