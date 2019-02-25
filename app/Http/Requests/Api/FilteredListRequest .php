<?php

namespace App\Http\Requests\Api;

use App\Extensions\ActivityPeriod\ActivityPeriodAssistant;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * Request with parameters to retrieve filtered list of items.
 */
class FilteredListRequest extends ApiRequest
{
    protected const SEARCH_ATTRIBUTE = 'search';
    protected const ACTIVE_FROM_ATTRIBUTE = ActivityPeriodAssistant::ACTIVE_FROM;
    protected const ACTIVE_TO_ATTRIBUTE = ActivityPeriodAssistant::ACTIVE_TO;
    protected const FILTERS_ATTRIBUTE = 'filters';

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
        ];
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
            if ($value === null ||
                is_array($value) ||
                (is_array($allowedFilters) && !in_array($filterParameter, $allowedFilters))
            ) {
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

        return Carbon::parse($activeFrom)->setTimezone(config('app.timezone'));
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

        return Carbon::parse($activeFrom)->setTimezone(config('app.timezone'));
    }
}
