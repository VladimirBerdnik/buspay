<?php

namespace App\Domain\Import\Dto;

use Saritasa\Dto;

/**
 * Transactions data from external storage. Represents authorization by transport card on bus validator device.
 *
 * @property-read integer $id External transaction identifier
 * @property-read integer $date Transaction date timestamp
 * @property-read integer $card_number Authorized card number (not card identifier)
 * @property-read integer|null $sum Payed during authorization amount
 * @property-read integer $validator_serial Serial number of validator that authorized card
 * @property-read integer|null $tariff_id Tariff identifier where card was authorized
 */
class ExternalTransactionData extends Dto
{
    public const ID = 'id';
    public const DATE = 'date';
    public const CARD_NUMBER = 'card_number';
    public const SUM = 'sum';
    public const VALIDATOR_SERIAL = 'validator_serial';
    public const TARIFF_ID = 'tariff_id';

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
    protected $card_number;

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
    protected $validator_serial;

    /**
     * Tariff identifier where card was authorized.
     *
     * @var integer|null
     */
    protected $tariff_id;
}
