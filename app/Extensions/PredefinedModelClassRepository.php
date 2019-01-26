<?php

namespace App\Extensions;

use App\Extensions\Dto\Criterion;
use App\Extensions\Exceptions\BadCriteriaException;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Saritasa\DingoApi\Paging\PagingInfo;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Repositories\Repository;

/**
 * {@inheritdoc}
 * Allows to declare handled model class in private property.
 */
class PredefinedModelClassRepository extends Repository implements IPartialDataRetrievingRepository
{
    /**
     * Available operators for operations with single value.
     *
     * @var string[]
     */
    protected $singleOperators = [
        '=',
        '<',
        '>',
        '<=',
        '>=',
        '<>',
        '!=',
        '<=>',
        'like',
        'like binary',
        'not like',
        'ilike',
        '&',
        '|',
        '^',
        '<<',
        '>>',
        'rlike',
        'regexp',
        'not regexp',
        '~',
        '~*',
        '!~',
        '!~*',
        'similar to',
        'not similar to',
        'not ilike',
        '~~*',
        '!~~*',
    ];

    /**
     * Available operators for operations with multiple values.
     *
     * @var string[]
     */
    protected $multipleOperators = ['in', 'not in'];

    /** {@inheritdoc} */
    public function __construct(?string $modelClass = null)
    {
        parent::__construct($modelClass ?? $this->modelClass);
    }

    /**
     * Transforms criterion data into DTO.
     *
     * @param mixed[] $criterionData Criterion data to transform
     *
     * @return Criterion
     */
    protected function parseCriterion(array $criterionData): Criterion
    {
        return new Criterion([
            Criterion::ATTRIBUTE => $criterionData[0] ?? null,
            Criterion::OPERATOR => $criterionData[1] ?? null,
            Criterion::VALUE => $criterionData[2] ?? null,
            Criterion::BOOLEAN => $criterionData[3] ?? 'and',
        ]);
    }

    /**
     * Checks whether the criterion is valid.
     *
     * @param Criterion $criterion Criterion to check validity
     *
     * @return boolean
     */
    protected function isCriterionValid(Criterion $criterion): bool
    {
        $validMultipleValuesCondition = (is_array($criterion->value) || $criterion->value instanceof Collection) &&
            in_array($criterion->operator, $this->multipleOperators);
        $isSingleOperator = !is_array($criterion->value) && in_array($criterion->operator, $this->singleOperators);

        return is_string($criterion->attribute) &&
            is_string($criterion->boolean) &&
            ($validMultipleValuesCondition || $isSingleOperator);
    }

    /**
     * Returns query builder with applied criteria. This method work recursively and group nested criteria in one level.
     *
     * @param Builder|QueryBuilder $builder Top level query builder
     * @param mixed[] $criteria Nested list of criteria
     *
     * @return Builder|QueryBuilder
     *
     * @throws BadCriteriaException when any criterion is not valid
     */
    protected function getNestedWhereConditions(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        $subQuery = $builder->forNestedWhere();
        foreach ($criteria as $key => $criterionData) {
            switch (true) {
                case is_string($key) && !is_array($criterionData) && !is_object($criterionData):
                    $criterion = new Criterion([Criterion::ATTRIBUTE => $key, Criterion::VALUE => $criterionData]);
                    break;

                case $criterionData instanceof Criterion:
                    $criterion = $criterionData;
                    break;
                case is_int($key) && is_array($criterionData) && !empty($criterionData):
                    $criterion = $this->parseCriterion($criterionData);
                    break;
                default:
                    throw new BadCriteriaException($this);
            }

            if (!$this->isCriterionValid($criterion)) {
                $subQuery->addNestedWhereQuery($this->getNestedWhereConditions($subQuery, $criterionData));
                continue;
            }

            switch ($criterion->operator) {
                case 'in':
                    $subQuery->whereIn($criterion->attribute, $criterion->value, $criterion->boolean);
                    break;
                case 'not in':
                    $subQuery->whereNotIn($criterion->attribute, $criterion->value, $criterion->boolean);
                    break;
                default:
                    if ($criterion->value instanceof Carbon) {
                        $subQuery->whereDate(
                            $criterion->attribute,
                            $criterion->operator,
                            $criterion->value,
                            $criterion->boolean
                        );
                        break;
                    }
                    $subQuery->where(
                        $criterion->attribute,
                        $criterion->operator,
                        $criterion->value,
                        $criterion->boolean
                    );
                    break;
            }
        }

        return $subQuery;
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

    /**
     * {@inheritdoc}
     *
     * @throws BadCriteriaException
     */
    public function getWhere(array $fieldValues): Collection
    {
        $builder = $this->query();

        return $builder
            ->addNestedWhereQuery($this->getNestedWhereConditions($builder->getQuery(), $fieldValues))
            ->get();
    }

    /**
     * {@inheritdoc}
     *
     * @throws BadCriteriaException
     */
    public function findWhere(array $fieldValues): ?Model
    {
        $builder = $this->query();

        return $builder
            ->addNestedWhereQuery($this->getNestedWhereConditions($builder->getQuery(), $fieldValues))
            ->first();
    }
}
