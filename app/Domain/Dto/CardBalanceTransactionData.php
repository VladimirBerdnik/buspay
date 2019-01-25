<?php

namespace App\Domain\Dto;

use App\Domain\Enums\CardTransactionsTypes;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Saritasa\Dto;

/**
 * Card balance transaction data. Contains information about transaction that changes card balance, like replenishment
 * or write-off.
 *
 * @property-read Carbon $date When transaction was performed
 * @property-read float $amount Amount of transaction. Can be zero or negative also
 * @property-read string $type Type of transaction
 *
 * @see CardTransactionsTypes for available transaction types
 */
class CardBalanceTransactionData extends Dto implements Arrayable
{
    public const DATE = 'date';
    public const AMOUNT = 'amount';
    public const TYPE = 'type';

    /**
     * When transaction was performed.
     *
     * @var Carbon
     */
    protected $date;

    /**
     * Amount of transaction. Can be zero or negative also.
     *
     * @var float
     */
    protected $amount;

    /**
     * Type of transaction.
     *
     * @var string
     */
    protected $type;
}
