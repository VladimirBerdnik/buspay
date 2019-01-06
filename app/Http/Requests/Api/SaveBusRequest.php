<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\BusFullData;
use App\Domain\Dto\BusRouteData;
use App\Models\Bus;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * Request to save bus details.
 */
class SaveBusRequest extends ApiRequest
{
    /**
     * Rules that should be applied to validate request.
     *
     * @return string[]|GenericRuleSet[]
     */
    public function rules(): array
    {
        return [
            Bus::COMPANY_ID => Rule::required(),
            Bus::ROUTE_ID => Rule::nullable()->int(),
            Bus::MODEL_NAME => Rule::required()->string()->max(24),
            Bus::STATE_NUMBER => Rule::required()->string()->max(10),
        ];
    }

    /**
     * Returns bus route only details.
     *
     * @return BusRouteData
     */
    public function getBusRouteData(): BusRouteData
    {
        return new BusRouteData($this->all());
    }

    /**
     * Returns bus full details.
     *
     * @return BusFullData
     */
    public function getBusFullData(): BusFullData
    {
        return new BusFullData($this->all());
    }
}
