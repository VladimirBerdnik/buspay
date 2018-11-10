<?php

namespace App\Extensions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Saritasa\DingoApi\Paging\PagingInfo;
use Saritasa\LaravelRepositories\DTO\SortOptions;

/**
 * Repository that can retrieve paginated filtered sorted list of items with related records and related records count.
 */
interface IPageRetrievingRepository
{
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
     */
    public function getPageWith(
        PagingInfo $paging,
        array $with,
        ?array $withCounts = null,
        ?array $where = null,
        ?SortOptions $sortOptions = null
    ): LengthAwarePaginator;
}
