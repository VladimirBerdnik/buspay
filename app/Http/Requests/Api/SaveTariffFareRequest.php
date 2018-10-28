<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\TariffFareData;
use App\Models\TariffFare;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * SaveTariffFareRequest form request.
 *
 * @property-read integer $tariff_id Tariff identifier to which this fare belongs
 * @property-read integer $card_type_id Card type identifier to which this fare applicable
 * @property-read integer $amount Road trip fare
 */
class SaveTariffFareRequest extends ApiRequest
{
    /**
     * Rules that should be applied to validate request.
     *
     * @return string[]|GenericRuleSet[]
     */
    public function rules(): array
    {
        return [
            TariffFare::TARIFF_ID => Rule::required()->exists('tariffs', 'id')->int(),
            TariffFare::CARD_TYPE_ID => Rule::required()->exists('card_types', 'id')->int(),
            TariffFare::AMOUNT => Rule::required()->int(),
        ];
    }

    /**
     * Returns tariff fare details.
     *
     * @return TariffFareData
     */
    public function getTariffFareData(): TariffFareData
    {
        return new TariffFareData($this->all());
    }
}
