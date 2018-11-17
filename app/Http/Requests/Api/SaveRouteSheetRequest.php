<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\RouteSheetData;
use App\Models\RouteSheet;
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
            RouteSheet::COMPANY_ID => Rule::exists('companies', 'id')->int(),
            RouteSheet::ROUTE_ID => Rule::nullable()->exists('routes', 'id')->int(),
            RouteSheet::BUS_ID => Rule::required()->exists('buses', 'id')->int(),
            RouteSheet::DRIVER_ID => Rule::nullable()->exists('drivers', 'id')->int(),
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
        return new RouteSheetData($this->all());
    }
}
