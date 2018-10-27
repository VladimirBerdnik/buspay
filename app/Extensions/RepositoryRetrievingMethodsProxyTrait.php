<?php

namespace App\Extensions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Saritasa\DingoApi\Paging\CursorRequest;
use Saritasa\DingoApi\Paging\CursorResult;
use Saritasa\DingoApi\Paging\PagingInfo;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Exceptions\ModelNotFoundException;

/**
 * Proxies access to repository's methods to retrieve handled models.
 */
trait RepositoryRetrievingMethodsProxyTrait
{
    /**
     * Find model by their id.
     *
     * @param string|int $id Model id to find
     *
     * @return Model
     *
     * @throws ModelNotFoundException
     */
    public function findOrFail($id): Model
    {
        return $this->getRepository()->findOrFail($id);
    }

    /**
     * Find model by their id. Returns new model when model with ID not found
     *
     * @param string|int $id Model id to find
     *
     * @return Model
     *
     * @throws ModelNotFoundException
     */
    public function findOrNew($id): Model
    {
        return $this->getRepository()->findOrNew($id);
    }

    /**
     * Returns first model matching given filters.
     *
     * @param mixed[] $fieldValues Filters collection
     *
     * @return Model|null
     */
    public function findWhere(array $fieldValues): Model
    {
        return $this->getRepository()->findWhere($fieldValues);
    }

    /**
     * Returns models list.
     *
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->getRepository()->get();
    }

    /**
     * Returns models list matching given filters.
     *
     * @param mixed[] $fieldValues Filters collection
     *
     * @return Collection
     */
    public function getWhere(array $fieldValues): Collection
    {
        return $this->getRepository()->getWhere($fieldValues);
    }

    /**
     * Retrieve list of entities that satisfied requested conditions.
     *
     * @param string[] $with Which relations should be preloaded
     * @param string[] $withCounts Which related entities should be counted
     * @param mixed[] $where Conditions that retrieved entities should satisfy
     * @param SortOptions $sortOptions How list of items should be sorted
     *
     * @return Collection
     */
    public function getWith(
        array $with,
        ?array $withCounts = null,
        ?array $where = null,
        ?SortOptions $sortOptions = null
    ): Collection {
        return $this->getRepository()->getWith($with, $withCounts, $where, $sortOptions);
    }

    /**
     * Get models collection as pagination.
     *
     * @param PagingInfo $paging Paging information
     * @param mixed[]|null $fieldValues Filters collection
     *
     * @return LengthAwarePaginator
     */
    public function getPage(PagingInfo $paging, ?array $fieldValues = null): LengthAwarePaginator
    {
        return $this->getRepository()->getPage($paging, $fieldValues);
    }

    /**
     * Get models collection as cursor.
     *
     * @param CursorRequest $cursor Request with cursor data
     * @param mixed[]|null $fieldValues Filters collection
     *
     * @return CursorResult
     */
    public function getCursorPage(CursorRequest $cursor, ?array $fieldValues = null): CursorResult
    {
        return $this->getRepository()->getCursorPage($cursor, $fieldValues);
    }
}
