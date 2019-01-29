<?php

namespace App\Domain\Import\Dto;

use Saritasa\Dto;

/**
 * Transactions data from external storage. Represents authorization by transport card on bus validator device.
 *
 * @property-read integer $id External transaction identifier
 * @property-read integer $date Transaction date timestamp
 * @property-read integer $card_number_id Authorized card number (not card identifier)
 * @property-read integer|null $sum Payed during authorization amount
 * @property-read integer $validators_id Serial number of validator that authorized card
 * @property-read integer $card_type_id Type of card that was authorized
 */
class ExternalTransactionData extends Dto
{
    public const ID = 'id';
    public const DATE = 'date';
    public const CARD_NUMBER_ID = 'card_number_id';
    public const SUM = 'sum';
    public const VALIDATORS_ID = 'validators_id';
    public const CARD_TYPE_ID = 'card_type_id';

    /**
     * External transaction identifier.
     *
     * @var integer
     */
    protected $id;

    /**
     * Transaction date timestamp.
     *
     * @var integer
     */
    protected $date;

    /**
     * Authorized card number (not card identifier).
     *
     * @var integer
     */
    protected $card_number_id;

    /**
     * Payed during authorization amount.
     *
     * @var integer|null
     */
    protected $sum;

    /**
     * Serial number of validator that authorized card.
     *
     * @var integer
     */
    protected $validators_id;

    /**
     * Type of card that was authorized.
     *
     * @var integer
     */
    protected $card_type_id;
}
