<?php

namespace App\Repositories;

use App\Domain\Dto\Filters\TransactionsFilterData;
use App\Extensions\PredefinedModelClassRepository as Repository;
use App\Models\Card;
use App\Models\Transaction;
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

                if ($filterData->driver_id) {
                    // TODO Driver should be filtered by period also
                }

                return $builder;
            });
        }

        // Filter by authorized validator parameters
        if ($filterData->bus_id || $filterData->route_id || $filterData->company_id) {
            // TODO Bus should be filtered by validator and validator to bus period
            // TODO Route should be filtered by bus and bus to route period
            // TODO Company should be filtered by bus and bus to company period
        }

        return $query->paginate($paging->pageSize, ['*'], 'page', $paging->page);
    }
}
