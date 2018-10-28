<?php

namespace App\Domain\Dto;

use Saritasa\Dto;

/**
 * Driver to Card details.
 *
 * @property-read integer $driver_id Linked to card driver identifier
 * @property-read integer $card_id Linked to driver card identifier
 * @property-read string $active_from Start date of activity period of this record
 * @property-read string|null $active_to End date of activity period of this record
 */
class DriversCardData extends Dto
{
    public const DRIVER_ID = 'driver_id';
    public const CARD_ID = 'card_id';
    public const ACTIVE_FROM = 'active_from';
    public const ACTIVE_TO = 'active_to';

    /**
     * Linked to card driver identifier.
     *
     * @var integer
     */
    protected $driver_id;

    /**
     * Linked to driver card identifier.
     *
     * @var integer
     */
    protected $card_id;

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
