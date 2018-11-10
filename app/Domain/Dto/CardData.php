<?php

namespace App\Domain\Dto;

use Saritasa\Dto;

/**
 * Card details.
 *
 * @property-read integer $card_type_id Card type
 * @property-read string $card_number Short card number, written on card case
 * @property-read string $uin Unique card number, patched to ROM
 * @property-read integer $active Is this card active or not
 */
class CardData extends Dto
{
    public const CARD_TYPE_ID = 'card_type_id';
    public const CARD_NUMBER = 'card_number';
    public const UIN = 'uin';
    public const ACTIVE = 'active';

    /**
     * Card type.
     *
     * @var integer
     */
    protected $card_type_id;

    /**
     * Short card number, written on card case.
     *
     * @var string
     */
    protected $card_number;

    /**
     * Unique card number, patched to ROM.
     *
     * @var string
     */
    protected $uin;

    /**
     * Is this card active or not.
     *
     * @var integer
     */
    protected $active;
}
