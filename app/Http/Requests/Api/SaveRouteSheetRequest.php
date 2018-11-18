<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\RouteSheetData;
use App\Models\RouteSheet;
use Carbon\Carbon;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * Request to save route sheet details.
 */
class SaveRouteSheetRequest extends ApiRequest
{
    /**
     * Rules that should be applied to validate request.
     *
     * @return string[]|GenericRuleSet[]
     */
    public function rules(): array
    {
        return [
            RouteSheet::COMPANY_ID => Rule::required()->int(),
            RouteSheet::ROUTE_ID => Rule::nullable()->int(),
            RouteSheet::BUS_ID => Rule::required()->int(),
            RouteSheet::DRIVER_ID => Rule::nullable()->int(),
            RouteSheet::ACTIVE_FROM => Rule::required()->date(),
            RouteSheet::ACTIVE_TO => Rule::nullable()->date(),
        ];
    }

    /**
     * Returns route sheet details.
     *
     * @return RouteSheetData
     */
    public function getRouteSheetData(): RouteSheetData
    {
        $parameters = $this->all();
        $parameters[RouteSheetData::ACTIVE_FROM] = $this->get(RouteSheetData::ACTIVE_FROM)
            ? Carbon::parse($this->get(RouteSheetData::ACTIVE_FROM))
            : null;
        $parameters[RouteSheetData::ACTIVE_TO] = $this->get(RouteSheetData::ACTIVE_TO)
            ? Carbon::parse($this->get(RouteSheetData::ACTIVE_TO))
            : null;

        return new RouteSheetData($parameters);
    }
}
