<?php

namespace App\Domain\Dto;

use Carbon\Carbon;
use Saritasa\Dto;

/**
 * Replenishment details.
 *
 * @property-read integer $card_id Identifier of card that was replenished
 * @property-read float $amount Amount of card replenishment
 * @property-read Carbon $replenished_at Date when card was replenished
 * @property-read integer $external_id Identifier of replenishment in external storage
 */
class ReplenishmentData extends Dto
{
    public const CARD_ID = 'card_id';
    public const AMOUNT = 'amount';
    public const REPLENISHED_AT = 'replenished_at';
    public const EXTERNAL_ID = 'external_id';

    /**
     * Identifier of card that was replenished.
     *
     * @var integer
     */
    protected $card_id;

    /**
     * Amount of card replenishment.
     *
     * @var float
     */
    protected $amount;

    /**
     * Date when card was replenished.
     *
     * @var Carbon
     */
    protected $replenished_at;

    /**
     * Identifier of replenishment in external storage.
     *
     * @var integer
     */
    protected $external_id;
}
