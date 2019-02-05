<?php

namespace App\Domain\Dto\Filters;

use Carbon\Carbon;
use Saritasa\Dto;

/**
 * Details to retrieve list of transactions.
 *
 * @property-read string|null $search_string Search string to perform custom filtering
 * @property-read Carbon|null $authorized_from Only transactions authorized after this date
 * @property-read Carbon|null $authorized_to Only transactions authorized before this date
 * @property-read integer|null $card_type_id Identifier of authorized card type
 * @property-read integer|null $tariff_id Identifier of tariff on which transaction was authorized
 * @property-read integer|null $company_id Identifier of company in which buses transactions was authorized
 * @property-read integer|null $validator_id Identifier of validator where transactions was authorized
 * @property-read integer|null $route_id Identifier of route where transaction was authorized
 * @property-read integer|null $bus_id Identifier of bus where transaction was authorized
 * @property-read integer|null $driver_id Identifier of driver that was authorized
 */
class TransactionsFilterData extends Dto
{
    public const SEARCH_STRING = 'search_string';
    public const AUTHORIZED_FROM = 'authorized_from';
    public const AUTHORIZED_TO = 'authorized_to';
    public const CARD_TYPE_ID = 'card_type_id';
    public const TARIFF_ID = 'tariff_id';
    public const COMPANY_ID = 'company_id';
    public const VALIDATOR_ID = 'validator_id';
    public const ROUTE_ID = 'route_id';
    public const BUS_ID = 'bus_id';
    public const DRIVER_ID = 'driver_id';

    /**
     * Search string to perform custom filtering.
     *
     * @var string|null
     */
    protected $search_string;

    /**
     * Only transactions authorized after this date.
     *
     * @var Carbon|null
     */
    protected $authorized_from;

    /**
     * Only transactions authorized before this date.
     *
     * @var Carbon|null
     */
    protected $authorized_to;

    /**
     * Identifier of authorized card type.
     *
     * @var integer|null
     */
    protected $card_type_id;

    /**
     * Identifier of tariff on which transaction was authorized.
     *
     * @var integer|null
     */
    protected $tariff_id;

    /**
     * Identifier of company in which buses transactions was authorized.
     *
     * @var integer|null
     */
    protected $company_id;

    /**
     * Identifier of validator where transactions was authorized,
     *
     * @var integer|null
     */
    protected $validator_id;

    /**
     * Identifier of route where transaction was authorized.
     *
     * @var integer|null
     */
    protected $route_id;

    /**
     * Identifier of bus where transaction was authorized.
     *
     * @var integer|null
     */
    protected $bus_id;

    /**
     * Identifier of driver that was authorized.
     *
     * @var integer|null
     */
    protected $driver_id;
}
