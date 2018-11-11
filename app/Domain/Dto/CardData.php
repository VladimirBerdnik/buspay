<?php

namespace App\Domain\Dto;

use Carbon\Carbon;
use Saritasa\Dto;

/**
 * Card details.
 *
 * @property-read integer $card_type_id Card type
 * @property-read integer $card_number Short card number, written on card case
 * @property-read integer $uin Unique card number, patched to ROM
 * @property-read integer $active Is this card active or not
 * @property-read string $synchronized_at Date when record was synchronized last time
 */
class CardData extends Dto
{
    public const CARD_TYPE_ID = 'card_type_id';
    public const CARD_NUMBER = 'card_number';
    public const UIN = 'uin';
    public const ACTIVE = 'active';
    public const SYNCHRONIZED_AT = 'synchronized_at';

    /**
     * Card type.
     *
     * @var integer
     */
    protected $card_type_id;

    /**
     * Short card number, written on card case.
     *
     * @var integer
     */
    protected $card_number;

    /**
     * Unique card number, patched to ROM.
     *
     * @var integer
     */
    protected $uin;

    /**
     * Is this card active or not.
     *
     * @var integer
     */
    protected $active;

    /**
     * Date when record was synchronized last time.
     *
     * @var Carbon
     */
    protected $synchronized_at;
}
