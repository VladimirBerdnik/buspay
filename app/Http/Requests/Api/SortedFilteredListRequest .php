<?php

namespace App\Http\Requests\Api;

use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Enums\OrderDirections;

/**
 * Request with parameters to retrieve sorted filtered list of items.
 */
class SortedFilteredListRequest extends FilteredListRequest
{
    protected const SORT_BY_ATTRIBUTE = 'sortBy';
    protected const DESCENDING_ATTRIBUTE = 'descending';

    /**
     * Rules that should be applied to validate request.
     *
     * @return string[]|GenericRuleSet[]
     */
    public function rules(): array
    {
        return array_merge(
            parent:: rules(),
            [
                static::SORT_BY_ATTRIBUTE => Rule::nullable()->string(),
                static::DESCENDING_ATTRIBUTE => Rule::nullable(),
            ]
        );
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
}
