<?php

namespace App\Extensions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Repositories\Repository;

/**
 * {@inheritdoc}
 * Allows to declare handled model class in private property.
 */
class PredefinedModelClassRepository extends Repository
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
}
