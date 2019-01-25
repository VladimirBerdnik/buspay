<?php

namespace App\Repositories;

use App\Extensions\PredefinedModelClassRepository as Repository;
use App\Models\Card;
use App\Models\Replenishment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Card replenishment records storage.
 */
class ReplenishmentRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = Replenishment::class;

    /**
     * Returns replenishment sum for card within given period.
     *
     * @param Card $card Card for which need to retrieve replenishment sum
     * @param Carbon|null $from Start of period of sum calculation
     * @param Carbon|null $to End of period of sum calculation
     *
     * @return float|null
     */
    public function getSumForPeriod(Card $card, ?Carbon $from = null, ?Carbon $to = null): ?float
    {
        return $this->query()
            ->where(Replenishment::CARD_ID, $card->id)
            ->when($from, function (Builder $builder) use ($from) {
                return $builder->whereDate(Replenishment::REPLENISHED_AT, '>=', $from);
            })
            ->when($to, function (Builder $builder) use ($to) {
                return $builder->whereDate(Replenishment::REPLENISHED_AT, '<=', $to);
            })
            ->sum(Replenishment::AMOUNT);
    }
}
