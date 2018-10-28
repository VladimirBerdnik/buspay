<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\CardData;
use App\Models\Card;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * SaveCardRequest form request.
 *
 * @property-read integer $card_type_id Card type
 * @property-read string $card_number Card authentication number
 */
class SaveCardRequest extends ApiRequest
{
    /**
     * Rules that should be applied to validate request.
     *
     * @return string[]|GenericRuleSet[]
     */
    public function rules(): array
    {
        return [
            Card::CARD_TYPE_ID => Rule::required()->exists('card_types', 'id')->int(),
            Card::CARD_NUMBER => Rule::required()->string()->max(191),
        ];
    }

    /**
     * Returns card details.
     *
     * @return CardData
     */
    public function getCardData(): CardData
    {
        return new CardData($this->all());
    }
}
