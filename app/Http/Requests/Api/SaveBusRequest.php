<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\BusData;
use App\Models\Bus;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * SaveBusRequest form request.
 *
 * @property-read integer $company_id Company identifier, to which this bus belongs
 * @property-read integer|null $route_id Usual route identifier, on which this bus is
 * @property-read string $model_name Name of bus model
 * @property-read string $state_number Bus state number
 * @property-read string|null $description Bus description or notes
 * @property-read boolean $active Does this bus works or not, can be assigned to route or not
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
            Bus::COMPANY_ID => Rule::required()->exists('companies', 'id')->int(),
            Bus::ROUTE_ID => Rule::nullable()->exists('routes', 'id')->int(),
            Bus::MODEL_NAME => Rule::required()->string()->max(191),
            Bus::STATE_NUMBER => Rule::required()->string()->max(191),
            Bus::DESCRIPTION => Rule::nullable()->string()->max(191),
            Bus::ACTIVE => Rule::required()->boolean(),
        ];
    }

    /**
     * Returns bus details.
     *
     * @return BusData
     */
    public function getBusData(): BusData
    {
        return new BusData($this->all());
    }
}
