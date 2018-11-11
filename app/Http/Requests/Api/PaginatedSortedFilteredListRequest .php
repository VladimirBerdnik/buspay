<?php

namespace App\Http\Requests\Api;

use Illuminate\Support\Str;
use Saritasa\DingoApi\Paging\PagingInfo;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Enums\OrderDirections;

/**
 * Request with parameters to retrieve paginated sorted filtered list of items.
 */
class PaginatedSortedFilteredListRequest extends ApiRequest
{
    /**
     * Returns requested by user page number and limits.
     *
     * @return PagingInfo
     */
    public function getPagingInfo(): PagingInfo
    {
        return new PagingInfo([
            'page' => $this->get('page'),
            'per_page' => $this->get('rowsPerPage'),
        ]);
    }

    /**
     * Returns requested by user sort options.
     *
     * @return SortOptions
     *
     * @throws InvalidEnumValueException
     */
    public function getSortOptions(): ?SortOptions
    {
        if (!$this->get('sortBy')) {
            return null;
        }

        return new SortOptions(
            $this->get('sortBy'),
            filter_var($this->get('descending'), FILTER_VALIDATE_BOOLEAN) ? OrderDirections::DESC : OrderDirections::ASC
        );
    }

    /**
     * Returns filters that result items should match.
     *
     * @return mixed[]
     */
    public function getFilters(): array
    {
        $filterString = $this->get('filters') ?? '{}';
        $filters = json_decode($filterString, true);
        $validFilters = [];
        foreach ($filters as $key => $value) {
            if (!$value || is_array($value)) {
                continue;
            }

            $validFilters[Str::snake($key)] = $value;
        }

        return $validFilters;
    }
}
