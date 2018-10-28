<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\BusesValidatorData;
use App\Models\BusesValidator;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * SaveBusesValidatorRequest form request.
 *
 * @property-read integer $bus_id Linked to validator bus identifier
 * @property-read integer $validator_id Linked to bus validator identifier
 * @property-read string $active_from Start date of activity period of this record
 * @property-read string|null $active_to End date of activity period of this record
 */
class SaveBusesValidatorRequest extends ApiRequest
{
    /**
     * Rules that should be applied to validate request.
     *
     * @return string[]|GenericRuleSet[]
     */
    public function rules(): array
    {
        return [
            BusesValidator::BUS_ID => Rule::required()->exists('buses', 'id')->int(),
            BusesValidator::VALIDATOR_ID => Rule::required()->exists('validators', 'id')->int(),
            BusesValidator::ACTIVE_FROM => Rule::required()->date(),
            BusesValidator::ACTIVE_TO => Rule::nullable()->date(),
        ];
    }

    /**
     * Returns bus validator details.
     *
     * @return BusesValidatorData
     */
    public function getBusValidatorData(): BusesValidatorData
    {
        return new BusesValidatorData($this->all());
    }
}
