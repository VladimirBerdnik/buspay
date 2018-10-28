<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\ValidatorData;
use App\Models\Validator;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * SaveValidatorRequest form request.
 *
 * @property-read integer|null $bus_id Current bus identifier where validator located
 * @property-read string $serial_number Validator serial number
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
            Validator::BUS_ID => Rule::nullable()->exists('buses', 'id')->int(),
            Validator::SERIAL_NUMBER => Rule::required()->string()->max(191),
        ];
    }

    /**
     * Returns validator details.
     *
     * @return ValidatorData
     */
    public function getValidatorData(): ValidatorData
    {
        return new ValidatorData($this->all());
    }
}
