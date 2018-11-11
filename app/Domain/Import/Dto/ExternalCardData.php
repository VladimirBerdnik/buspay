<?php

namespace App\Domain\Import\Dto;

use Saritasa\Dto;

/**
 * Card details from external storage.
 *
 * @property-read integer $card_type Card type identifier
 * @property-read integer $card_number Card number, written on card case
 * @property-read integer $uin Card unique number, patched into ROM
 * @property-read boolean $enable Whether this card enabled or not
 * @property-read string $updated_at Date when record was updated last time
 */
class ExternalCardData extends Dto
{
    public const CARD_TYPE = 'card_type';
    public const CARD_NUMBER = 'card_number';
    public const UIN = 'uin';
    public const ENABLE = 'enable';
    public const UPDATED_AT = 'updated_at';

    /**
     * Card type identifier.
     *
     * @var integer
     */
    protected $card_type;

    /**
     * Card number, written on card case.
     *
     * @var integer
     */
    protected $card_number;

    /**
     * Card unique number, patched into ROM.
     *
     * @var integer
     */
    protected $uin;

    /**
     * Whether this card enabled or not.
     *
     * @var boolean
     */
    protected $enable;

    /**
     * Date when record was updated last time.
     *
     * @var string
     */
    protected $updated_at;
}
