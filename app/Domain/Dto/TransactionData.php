<?php

namespace App\Domain\Dto;

use Carbon\Carbon;
use Saritasa\Dto;

/**
 * Card transaction details. Represents transport card authorization on validator device with write-off amount from card
 * balance.
 *
 * @property-read integer $card_id Authorized card identifier
 * @property-read integer $validator_id Validator on which card was authorized
 * @property-read integer|null $tariff_id Tariff identifier with which card was authorized
 * @property-read Carbon $authorized_at Date when card was authorized
 * @property-read integer|null $amount Tariff amount that was written-off from card
 * @property-read integer $external_id Identifier of transaction in external storage
 */
class TransactionData extends Dto
{
    public const CARD_ID = 'card_id';
    public const VALIDATOR_ID = 'validator_id';
    public const TARIFF_ID = 'tariff_id';
    public const AUTHORIZED_AT = 'authorized_at';
    public const AMOUNT = 'amount';
    public const EXTERNAL_ID = 'external_id';

    /**
     * Authorized card identifier.
     *
     * @var integer
     */
    protected $card_id;

    /**
     * Validator on which card was authorized.
     *
     * @var integer
     */
    protected $validator_id;

    /**
     * Tariff identifier with which card was authorized.
     *
     * @var integer|null
     */
    protected $tariff_id;

    /**
     * Date when card was authorized.
     *
     * @var Carbon
     */
    protected $authorized_at;

    /**
     * Tariff amount that was written-off from card.
     *
     * @var integer|null
     */
    protected $amount;

    /**
     * Identifier of transaction in external storage.
     *
     * @var integer
     */
    protected $external_id;
}
