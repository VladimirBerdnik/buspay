<?php

namespace App\Repositories;

use App\Domain\Dto\Filters\TransactionsFilterData;
use App\Extensions\PredefinedModelClassRepository as Repository;
use App\Models\Card;
use App\Models\RouteSheet;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Saritasa\DingoApi\Paging\PagingInfo;
use Saritasa\LaravelRepositories\DTO\SortOptions;

/**
 * Transaction records storage.
 */
class TransactionRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = Transaction::class;

    /**
     * Returns payed during authorization on validators sum for card within given period.
     *
     * @param Card $card Card for which need to retrieve payed during authorization on validators sum
     * @param Carbon|null $from Start of period of sum calculation
     * @param Carbon|null $to End of period of sum calculation
     *
     * @return float|null
     */
    public function getSumForPeriod(Card $card, ?Carbon $from = null, ?Carbon $to = null): ?float
    {
        return $this->query()
            ->where(Transaction::CARD_ID, $card->id)
            ->when($from, function (Builder $builder) use ($from) {
                return $builder->whereDate(Transaction::AUTHORIZED_AT, '>=', $from);
            })
            ->when($to, function (Builder $builder) use ($to) {
                return $builder->whereDate(Transaction::AUTHORIZED_AT, '<=', $to);
            })
            ->sum(Transaction::AMOUNT);
    }

    /**
     * Returns paginated sorted list of optionally filtered items.
     *
     * @param PagingInfo $paging Page size and limits information
     * @param string[] $with Which relations should be preloaded
     * @param string[]|null $withCounts Which related entities should be counted
     * @param TransactionsFilterData|null $filterData Items parametrized filter details
     * @param SortOptions $sortOptions How list of item should be sorted
     *
     * @return LengthAwarePaginator
     */
    public function getFilteredPageWith(
        PagingInfo $paging,
        array $with,
        ?array $withCounts = null,
        ?TransactionsFilterData $filterData = null,
        ?SortOptions $sortOptions = null
    ): LengthAwarePaginator {
        $query = $this->getWithBuilder($with, $withCounts, null, $sortOptions);

        if ($filterData->authorized_from) {
            $query->where(Transaction::AUTHORIZED_AT, '>=', $filterData->authorized_from);
        }

        if ($filterData->authorized_to) {
            $query->where(Transaction::AUTHORIZED_AT, '<=', $filterData->authorized_to);
        }

        if ($filterData->tariff_id) {
            $query->where(Transaction::TARIFF_ID, $filterData->tariff_id);
        }

        if ($filterData->validator_id) {
            $query->where(Transaction::VALIDATOR_ID, $filterData->validator_id);
        }

        // Filter by authorized card parameters
        if ($filterData->card_type_id || $filterData->driver_id || $filterData->search_string) {
            $query->whereHas('card', function (Builder $builder) use ($filterData): Builder {
                if ($filterData->card_type_id) {
                    $builder->where(Card::CARD_TYPE_ID, '=', $filterData->card_type_id);
                }
                if ($filterData->search_string) {
                    $builder->where(Card::CARD_NUMBER, '=', $filterData->search_string);
                }

                return $builder;
            });
        }

        // Filter by authorized validator parameters
        if ($filterData->bus_id || $filterData->route_id || $filterData->company_id || $filterData->driver_id) {
            $query->whereHas('routeSheet', function (Builder $builder) use ($filterData): void {
                if ($filterData->bus_id) {
                    $builder->where(RouteSheet::BUS_ID, '=', $filterData->bus_id);
                }
                if ($filterData->route_id) {
                    $builder->where(RouteSheet::ROUTE_ID, '=', $filterData->route_id);
                }
                if ($filterData->driver_id) {
                    $builder->where(RouteSheet::DRIVER_ID, '=', $filterData->driver_id);
                }
                if ($filterData->company_id) {
                    $builder->where(RouteSheet::COMPANY_ID, '=', $filterData->company_id);
                }
            });
        }

        return $query->paginate($paging->pageSize, ['*'], 'page', $paging->page);
    }
}
