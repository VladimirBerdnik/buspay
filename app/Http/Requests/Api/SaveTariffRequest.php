<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\TariffData;
use App\Models\Tariff;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * SaveTariffRequest form request.
 *
 * @property-read string $active_from Start date of activity period of this record
 * @property-read string|null $active_to End date of activity period of this record
 */
class SaveTariffRequest extends ApiRequest
{
    /**
     * Rules that should be applied to validate request.
     *
     * @return string[]|GenericRuleSet[]
     */
    public function rules(): array
    {
        return [
            Tariff::ACTIVE_FROM => Rule::required()->date(),
            Tariff::ACTIVE_TO => Rule::nullable()->date(),
        ];
    }

    /**
     * Returns tariff details.
     *
     * @return TariffData
     */
    public function getTariffData(): TariffData
    {
        return new TariffData($this->all());
    }
}
