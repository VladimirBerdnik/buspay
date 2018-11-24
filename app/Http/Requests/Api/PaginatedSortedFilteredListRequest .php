<?php

namespace App\Http\Requests\Api;

use App\Extensions\ActivityPeriod\ActivityPeriodAssistant;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Saritasa\DingoApi\Paging\PagingInfo;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Enums\OrderDirections;

/**
 * Request with parameters to retrieve paginated sorted filtered list of items.
 */
class PaginatedSortedFilteredListRequest extends ApiRequest
{
    protected const SEARCH_ATTRIBUTE = 'search';
    protected const ACTIVE_FROM_ATTRIBUTE = ActivityPeriodAssistant::ACTIVE_FROM;
    protected const ACTIVE_TO_ATTRIBUTE = ActivityPeriodAssistant::ACTIVE_TO;
    protected const FILTERS_ATTRIBUTE = 'filters';
    protected const PAGE_ATTRIBUTE = 'page';
    protected const PER_PAGE_ATTRIBUTE = 'rowsPerPage';
    protected const SORT_BY_ATTRIBUTE = 'sortBy';
    protected const DESCENDING_ATTRIBUTE = 'descending';

    /**
     * Rules that should be applied to validate request.
     *
     * @return string[]|GenericRuleSet[]
     */
    public function rules(): array
    {
        return [
            static::SEARCH_ATTRIBUTE => Rule::nullable()->string(),
            static::ACTIVE_FROM_ATTRIBUTE => Rule::nullable()->date(),
            static::ACTIVE_TO_ATTRIBUTE => Rule::nullable()->date(),
            static::FILTERS_ATTRIBUTE => Rule::nullable()->string()->json(),
            static::PAGE_ATTRIBUTE => Rule::nullable()->int()->min(1),
            static::PER_PAGE_ATTRIBUTE => Rule::nullable()->int()->min(1)->max(100),
            static::SORT_BY_ATTRIBUTE => Rule::nullable()->string(),
            static::DESCENDING_ATTRIBUTE => Rule::nullable(),
        ];
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

    /**
     * Returns requested by user sort options.
     *
     * @return SortOptions
     *
     * @throws InvalidEnumValueException
     */
    public function getSortOptions(): ?SortOptions
    {
        if (!$this->get(static::SORT_BY_ATTRIBUTE)) {
            return null;
        }

        return new SortOptions(
            $this->get(static::SORT_BY_ATTRIBUTE),
            filter_var($this->get(static::DESCENDING_ATTRIBUTE), FILTER_VALIDATE_BOOLEAN)
                ? OrderDirections::DESC
                : OrderDirections::ASC
        );
    }

    /**
     * Returns exact filters that result items should match.
     *
     * @param string[]|null $allowedFilters List of filters to return only this filters
     *
     * @return mixed[]
     */
    public function getFilters(?array $allowedFilters = null): array
    {
        $filterString = $this->get(static::FILTERS_ATTRIBUTE) ?? '{}';
        $filters = json_decode($filterString, true);

        $validFilters = [];
        foreach ($filters as $key => $value) {
            $filterParameter = Str::snake($key);
            if (!$value || is_array($value) || ($allowedFilters && !in_array($filterParameter, $allowedFilters))) {
                continue;
            }

            $validFilters[$filterParameter] = $value;
        }

        return $validFilters;
    }

    /**
     * Returns common search string that should be applied to items.
     * Developer should decide how to deal with this string - apply to all items properties or not.
     *
     * @return string|null
     */
    public function getSearchString(): ?string
    {
        return trim($this->get(static::SEARCH_ATTRIBUTE));
    }

    /**
     * Returns start date of activity periods filter.
     *
     * @return Carbon|null
     */
    public function activeFrom(): ?Carbon
    {
        $activeFrom = $this->get(static::ACTIVE_FROM_ATTRIBUTE);
        if (!$activeFrom) {
            return null;
        }

        return Carbon::parse($activeFrom);
    }

    /**
     * Returns end date of activity periods filter.
     *
     * @return Carbon|null
     */
    public function activeTo(): ?Carbon
    {
        $activeFrom = $this->get(static::ACTIVE_TO_ATTRIBUTE);
        if (!$activeFrom) {
            return null;
        }

        return Carbon::parse($activeFrom);
    }
}
