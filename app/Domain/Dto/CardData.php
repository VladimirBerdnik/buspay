<?php

namespace App\Domain\Dto;

use Saritasa\Dto;

/**
 * Card details.
 *
 * @property-read integer $card_type_id Card type
 * @property-read string $card_number Card authentication number
 */
class CardData extends Dto
{
    public const CARD_TYPE_ID = 'card_type_id';
    public const CARD_NUMBER = 'card_number';

    /**
     * Card type.
     *
     * @var integer
     */
    protected $card_type_id;

    /**
     * Card authentication number.
     *
     * @var string
     */
    protected $card_number;
}
