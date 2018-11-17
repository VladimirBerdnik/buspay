<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\DriverData;
use App\Models\Driver;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * Request to save driver details.
 */
class SaveDriverRequest extends ApiRequest
{
    /**
     * Rules that should be applied to validate request.
     *
     * @return string[]|GenericRuleSet[]
     */
    public function rules(): array
    {
        return [
            Driver::FULL_NAME => Rule::required()->string()->max(96),
            Driver::COMPANY_ID => Rule::required()->int(),
            Driver::BUS_ID => Rule::nullable()->int(),
            Driver::CARD_ID => Rule::nullable()->int(),
        ];
    }

    /**
     * Returns driver details.
     *
     * @return DriverData
     */
    public function getDriverData(): DriverData
    {
        return new DriverData($this->all());
    }
}
