<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\DriverData;
use App\Models\Driver;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * SaveDriverRequest form request.
 *
 * @property-read integer $company_id Company identifier in which this driver works
 * @property-read integer|null $bus_id Bus identifier, on which this driver usually works
 * @property-read integer|null $card_id Current driver card identifier
 * @property-read string $full_name Driver full name
 * @property-read boolean $active Does this driver works or not, can be assigned to route or not
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
            Driver::COMPANY_ID => Rule::required()->exists('companies', 'id')->int(),
            Driver::BUS_ID => Rule::nullable()->exists('buses', 'id')->int(),
            Driver::CARD_ID => Rule::nullable()->exists('cards', 'id')->int(),
            Driver::FULL_NAME => Rule::required()->string()->max(191),
            Driver::ACTIVE => Rule::required()->boolean(),
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
