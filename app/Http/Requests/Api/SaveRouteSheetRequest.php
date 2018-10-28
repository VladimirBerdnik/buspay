<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\RouteSheetData;
use App\Models\RouteSheet;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * SaveRouteSheetRequest form request.
 *
 * @property-read integer|null $route_id Bus route identifier, which served the driver on the bus
 * @property-read integer $bus_id Bus identifier that is on route
 * @property-read integer|null $driver_id Driver identifier that is on bus on route
 * @property-read boolean $temporary Is this route sheet temporary (reserve) or not
 * @property-read string $active_from Start date of activity period of this record
 * @property-read string|null $active_to End date of activity period of this record
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
            RouteSheet::ROUTE_ID => Rule::nullable()->exists('routes', 'id')->int(),
            RouteSheet::BUS_ID => Rule::required()->exists('buses', 'id')->int(),
            RouteSheet::DRIVER_ID => Rule::nullable()->exists('drivers', 'id')->int(),
            RouteSheet::TEMPORARY => Rule::required()->boolean(),
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
