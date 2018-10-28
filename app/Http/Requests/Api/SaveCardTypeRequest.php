<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\CardTypeData;
use App\Models\CardType;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * SaveCardTypeRequest form request.
 *
 * @property-read string $slug Type machine-readable text identifier
 */
class SaveCardTypeRequest extends ApiRequest
{
    /**
     * Rules that should be applied to validate request.
     *
     * @return string[]|GenericRuleSet[]
     */
    public function rules(): array
    {
        return [
            CardType::SLUG => Rule::required()->string()->max(32),
        ];
    }

    /**
     * Returns card type details.
     *
     * @return CardTypeData
     */
    public function getCardTypeData(): CardTypeData
    {
        return new CardTypeData($this->all());
    }
}
