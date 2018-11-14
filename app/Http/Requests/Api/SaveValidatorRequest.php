<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\ValidatorBusData;
use App\Models\Validator;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * Request to assign validator to bus.
 *
 * @property-read integer|null $bus_id Bus identifier where validator located
 */
class SaveValidatorRequest extends ApiRequest
{
    /**
     * Rules that should be applied to validate request.
     *
     * @return string[]|GenericRuleSet[]
     */
    public function rules(): array
    {
        return [
            Validator::BUS_ID => Rule::nullable()->int(),
        ];
    }

    /**
     * Returns validator details.
     *
     * @return ValidatorBusData
     */
    public function getValidatorBusData(): ValidatorBusData
    {
        return new ValidatorBusData($this->all());
    }
}
