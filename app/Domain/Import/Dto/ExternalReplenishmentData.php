<?php

namespace App\Domain\Import\Dto;

use Saritasa\Dto;

/**
 * Replenishment data from external storage.
 *
 * @property-read integer $id External replenishment identifier
 * @property-read integer $txn_id Replenishment transaction unique identifier
 * @property-read integer $txn_date Replenishment transaction date and time
 * @property-read integer $account Replenished card number
 * @property-read float $sum Replenishment amount
 * @property-read integer $pay_type Replenishment type identifier
 * @property-read integer $trm_id Device identifier on whish replenishment was performed
 */
class ExternalReplenishmentData extends Dto
{
    public const ID = 'id';
    public const TXN_ID = 'txn_id';
    public const TXN_DATE = 'txn_date';
    public const ACCOUNT = 'account';
    public const SUM = 'sum';
    public const PAY_TYPE = 'pay_type';
    public const TRM_ID = 'trm_id';

    /**
     * External replenishment identifier.
     *
     * @var integer
     */
    protected $id;

    /**
     * Replenishment transaction unique identifier.
     *
     * @var integer
     */
    protected $txn_id;

    /**
     * Replenishment transaction date and time.
     *
     * @var integer
     */
    protected $txn_date;

    /**
     * Replenished card number.
     *
     * @var integer
     */
    protected $account;

    /**
     * Replenishment amount.
     *
     * @var float
     */
    protected $sum;

    /**
     * Replenishment type identifier.
     *
     * @var integer
     */
    protected $pay_type;

    /**
     * Device identifier on whish replenishment was performed.
     *
     * @var integer
     */
    protected $trm_id;
}
