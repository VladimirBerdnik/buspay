<?php

namespace App\Domain\Services;

use App\Domain\Dto\CardBalanceTransactionData;
use App\Domain\Enums\CardTransactionsTypes;
use App\Models\Card;
use App\Models\Replenishment;
use App\Repositories\ReplenishmentRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Enums\OrderDirections;

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
     * Card balance service. Allows to retrieve card balance total, replenishment adn write-off records.
     *
     * @param ReplenishmentRepository $replenishmentRepository Replenishment records storage
     */
    public function __construct(ReplenishmentRepository $replenishmentRepository)
    {
        $this->replenishmentRepository = $replenishmentRepository;
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
        $writeOffTotals = 0;

        return $replenishmentTotals - $writeOffTotals;
    }

    /**
     * Returns list of card balance transactions.
     *
     * @param Card $card Card for which need to retrieve transactions
     * @param Carbon|null $from Start of period of transactions to return
     * @param Carbon|null $to End of period of transactions to return
     *
     * @return Collection|CardBalanceTransactionData[]
     *
     * @throws InvalidEnumValueException
     */
    public function getTransactions(Card $card, ?Carbon $from = null, ?Carbon $to = null): Collection
    {
        $replenishmentsFilter = [Replenishment::CARD_ID => $card->id];
        if ($from) {
            $replenishmentsFilter[] = [Replenishment::REPLENISHED_AT, '>=', $from];
        }
        if ($to) {
            $replenishmentsFilter[] = [Replenishment::REPLENISHED_AT, '>=', $to];
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

        $transactions = new Collection();

        foreach ($replenishments as $replenishment) {
            $transactions->push(new CardBalanceTransactionData([
                CardBalanceTransactionData::AMOUNT => $replenishment->amount,
                CardBalanceTransactionData::TYPE => CardTransactionsTypes::REPLENISHMENT,
                CardBalanceTransactionData::DATE => $replenishment->replenished_at,
            ]));
            // Fake write-offs records
            $transactions->push(new CardBalanceTransactionData([
                CardBalanceTransactionData::AMOUNT => -80,
                CardBalanceTransactionData::TYPE => CardTransactionsTypes::WRITE_OFF,
                CardBalanceTransactionData::DATE => $replenishment->replenished_at->copy()->addMinutes(10),
            ]));
            $transactions->push(new CardBalanceTransactionData([
                CardBalanceTransactionData::AMOUNT => -80,
                CardBalanceTransactionData::TYPE => CardTransactionsTypes::WRITE_OFF,
                CardBalanceTransactionData::DATE => $replenishment->replenished_at->copy()->addMinutes(3),
            ]));
        }

        return $transactions;
    }
}
