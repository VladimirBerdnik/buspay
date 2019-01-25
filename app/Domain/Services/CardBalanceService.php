<?php

namespace App\Domain\Services;

use App\Models\Card;
use App\Repositories\ReplenishmentRepository;

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
}
