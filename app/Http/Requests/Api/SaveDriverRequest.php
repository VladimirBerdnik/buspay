<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\DriverBusData;
use App\Domain\Dto\DriverFullData;
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
     * Returns driver bus details.
     *
     * @return DriverBusData
     */
    public function getDriverBusData(): DriverBusData
    {
        return new DriverBusData($this->all());
    }

    /**
     * Returns driver full details.
     *
     * @return DriverFullData
     */
    public function getDriverFullData(): DriverFullData
    {
        return new DriverFullData($this->all());
    }
}
