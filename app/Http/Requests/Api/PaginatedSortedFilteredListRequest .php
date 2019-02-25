<?php

namespace App\Http\Requests\Api;

use Saritasa\DingoApi\Paging\PagingInfo;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * Request with parameters to retrieve paginated sorted filtered list of items.
 */
class PaginatedSortedFilteredListRequest extends SortedFilteredListRequest
{
    protected const PAGE_ATTRIBUTE = 'page';
    protected const PER_PAGE_ATTRIBUTE = 'rowsPerPage';

    /**
     * Rules that should be applied to validate request.
     *
     * @return string[]|GenericRuleSet[]
     */
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                static::PAGE_ATTRIBUTE => Rule::nullable()->int()->min(1),
                static::PER_PAGE_ATTRIBUTE => Rule::nullable()->int()->min(1)->max(100),
            ]
        );
    }

    /**
     * Returns requested by user page number and limits.
     *
     * @return PagingInfo
     */
    public function getPagingInfo(): PagingInfo
    {
        return new PagingInfo([
            'page' => $this->get(static::PAGE_ATTRIBUTE),
            'per_page' => $this->get(static::PER_PAGE_ATTRIBUTE),
        ]);
    }
}
