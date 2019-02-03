<?php

namespace App\Extensions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Saritasa\DingoApi\Paging\PagingInfo;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Exceptions\BadCriteriaException;
use Saritasa\LaravelRepositories\Repositories\Repository;

/**
 * {@inheritdoc}
 * Allows to declare handled model class in private property.
 */
class PredefinedModelClassRepository extends Repository implements IPartialDataRetrievingRepository
{
    /** {@inheritdoc} */
    public function __construct(?string $modelClass = null)
    {
        parent::__construct($modelClass ?? $this->modelClass);
    }

    /** {@inheritdoc} */
    protected function getWithBuilder(
        array $with,
        ?array $withCounts = null,
        ?array $where = null,
        ?SortOptions $sortOptions = null
    ): Builder {
        return $this->query()
            ->when($with, function (Builder $query) use ($with) {
                foreach ($with as $key => $value) {
                    if (is_int($key)) {
                        // If key is int we can assume that no constraints are passed
                        $query->with($value);
                    } elseif (is_string($key) && is_array($value)) {
                        // If key is string and value is array we can assume that constraints are key-value pairs
                        $query->with([
                            $key => function (Relation $relation) use ($value) {
                                return $relation->where($value);
                            },
                        ]);
                    } else {
                        $query->with([$key => $value]);
                    }
                }

                return $query;
            })
            ->when($withCounts, function (Builder $query) use ($withCounts) {
                return $query->withCount($withCounts);
            })
            ->when($where, function (Builder $query) use ($where) {
                return $query->where($where);
            })
            ->when($sortOptions, function (Builder $query) use ($sortOptions) {
                return $query->orderBy($sortOptions->orderBy, $sortOptions->sortOrder);
            });
    }

    /**
     * Returns paginated sorted list of optionally filtered items.
     *
     * @param PagingInfo $paging Page size and limits information
     * @param string[] $with Which relations should be preloaded
     * @param string[]|null $withCounts Which related entities should be counted
     * @param string[]|null $where Conditions that retrieved entities should satisfy
     * @param SortOptions $sortOptions How list of item should be sorted
     *
     * @return LengthAwarePaginator
     *
     * @throws BadCriteriaException
     */
    public function getPageWith(
        PagingInfo $paging,
        array $with,
        ?array $withCounts = null,
        ?array $where = null,
        ?SortOptions $sortOptions = null
    ): LengthAwarePaginator {
        $query = $this->getWithBuilder($with, $withCounts, null, $sortOptions);

        if ($where) {
            $query->addNestedWhereQuery($this->getNestedWhereConditions($query->getQuery(), $where));
        }

        return $query->paginate($paging->pageSize, ['*'], 'page', $paging->page);
    }

    /**
     * Executes passed callback for each chunk of filtered sorted collection of items with preloaded relations.
     *
     * @param string[] $with Which relations should be preloaded
     * @param string[]|null $withCounts Which related entities should be counted
     * @param string[]|null $where Conditions that retrieved entities should satisfy
     * @param SortOptions $sortOptions How list of item should be sorted
     * @param int $chunkSize Count of items that should be passed in collection of items into callback
     * @param callable $callback Callback that should be executed for every collection of items with given size
     *
     * @return boolean
     *
     * @throws BadCriteriaException
     */
    public function chunkWith(
        array $with,
        ?array $withCounts,
        ?array $where,
        ?SortOptions $sortOptions,
        int $chunkSize,
        callable $callback
    ): bool {
        $query = $this->getWithBuilder($with, $withCounts, null, $sortOptions);

        if ($where) {
            $query->addNestedWhereQuery($this->getNestedWhereConditions($query->getQuery(), $where));
        }

        return $query->chunk($chunkSize, $callback);
    }
}
