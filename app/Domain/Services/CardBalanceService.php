<?php

namespace App\Domain\Services;

use App\Domain\Dto\CardBalanceRecordData;
use App\Domain\Enums\CardTransactionsTypes;
use App\Models\Card;
use App\Models\Replenishment;
use App\Models\Transaction;
use App\Repositories\ReplenishmentRepository;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Enums\OrderDirections;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;

/**
 * Card balance service. Allows to retrieve card balance total, replenishment adn write-off records.
 */
class CardBalanceService
{
    /**
     * Replenishment records storage.
     *
     * @var ReplenishmentRepository
     */
    private $replenishmentRepository;

    /**
     * Transactions records storage.
     *
     * @var TransactionRepository
     */
    private $transactionRepository;

    /**
     * Card balance service. Allows to retrieve card balance total, replenishment adn write-off records.
     *
     * @param ReplenishmentRepository $replenishmentRepository Replenishment records storage
     * @param TransactionRepository $transactionRepository Transactions records storage
     */
    public function __construct(
        ReplenishmentRepository $replenishmentRepository,
        TransactionRepository $transactionRepository
    ) {
        $this->replenishmentRepository = $replenishmentRepository;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Returns given card totals.
     *
     * @param Card $card Card to retrieve totals information for
     *
     * @return float|null
     */
    public function getTotal(Card $card): ?float
    {
        $replenishmentTotals = $this->replenishmentRepository->getSumForPeriod($card);
        $writeOffTotals = $this->transactionRepository->getSumForPeriod($card);

        return $replenishmentTotals - $writeOffTotals;
    }

    /**
     * Returns list of card balance transactions.
     *
     * @param Card $card Card for which need to retrieve transactions
     * @param Carbon|null $from Start of period of transactions to return
     * @param Carbon|null $to End of period of transactions to return
     *
     * @return Collection|CardBalanceRecordData[]
     *
     * @throws InvalidEnumValueException
     * @throws RepositoryException
     */
    public function getTransactions(Card $card, ?Carbon $from = null, ?Carbon $to = null): Collection
    {
        $replenishmentsFilter = [Replenishment::CARD_ID => $card->id];
        $transactionsFilter = [Transaction::CARD_ID => $card->id];
        if ($from) {
            $replenishmentsFilter[] = [Replenishment::REPLENISHED_AT, '>=', $from];
            $transactionsFilter[] = [Transaction::AUTHORIZED_AT, '>=', $from];
        }
        if ($to) {
            $replenishmentsFilter[] = [Replenishment::REPLENISHED_AT, '<=', $to];
            $transactionsFilter[] = [Transaction::AUTHORIZED_AT, '<=', $to];
        }

        /**
         * Replenishment records within requested period.
         *
         * @var Replenishment[] $replenishments
         */
        $replenishments = $this->replenishmentRepository->getWith(
            [],
            [],
            $replenishmentsFilter,
            new SortOptions(Replenishment::REPLENISHED_AT, OrderDirections::DESC)
        );

        $records = new Collection();

        foreach ($replenishments as $replenishment) {
            $records->push(new CardBalanceRecordData([
                CardBalanceRecordData::AMOUNT => $replenishment->amount,
                CardBalanceRecordData::TYPE => CardTransactionsTypes::REPLENISHMENT,
                CardBalanceRecordData::DATE => $replenishment->replenished_at,
            ]));
        }

        /**
         * Replenishment records within requested period.
         *
         * @var Transaction[] $transactions
         */
        $transactions = $this->transactionRepository->getWith(
            [],
            [],
            $replenishmentsFilter,
            new SortOptions(Transaction::AUTHORIZED_AT, OrderDirections::DESC)
        );

        foreach ($transactions as $transaction) {
            $records->push(new CardBalanceRecordData([
                CardBalanceRecordData::AMOUNT => $transaction->amount,
                CardBalanceRecordData::TYPE => CardTransactionsTypes::WRITE_OFF,
                CardBalanceRecordData::DATE => $transaction->authorized_at,
            ]));
        }

        return $records->sortByDesc(CardBalanceRecordData::DATE);
    }
}
