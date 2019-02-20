<?php

namespace App\Http\Transformers\Api;

use App\Domain\Enums\CardTypesIdentifiers;
use App\Models\Card;

/**
 * Transforms card to display impersonated card details.
 */
class ImpersonalCardTransformer extends CardTransformer
{
    /**
     * Transforms model into appropriate format.
     *
     * @param Card $card Card to transform
     *
     * @return string[]
     */
    protected function transformModel(Card $card): array
    {
        return [
            Card::ID => null,
            Card::CARD_TYPE_ID => $card->card_type_id,
            Card::UIN => null,
            Card::CARD_NUMBER => $card->card_type_id === CardTypesIdentifiers::DRIVER ? $card->card_number : '********',
            Card::ACTIVE => null,
            Card::SYNCHRONIZED_AT => null,
        ];
    }
}
