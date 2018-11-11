<?php

namespace App\Domain\Services;

use App\Domain\Dto\CardData;
use App\Domain\Exceptions\Constraint\CardReassignException;
use App\Extensions\EntityService;
use App\Models\Card;
use App\Models\CardType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Throwable;

/**
 * Card business-logic service.
 */
class CardService extends EntityService
{
    /**
     * Returns validation rule to store or update card.
     *
     * @param Card $card Card to build rules for
     *
     * @return string[]|GenericRuleSet[]
     */
    protected function getCardValidationRules(Card $card): array
    {
        return [
            Card::CARD_TYPE_ID => Rule::required()->exists('card_types', CardType::ID),
            Card::CARD_NUMBER => Rule::required()
                // Card number should be unique
                ->unique('cards', Card::CARD_NUMBER, function (Unique $rule) use ($card) {
                    if ($card->exists) {
                        $rule->whereNot(Card::ID, $card->id);
                    }

                    return $rule->whereNull(Card::DELETED_AT);
                }),
            Card::UIN => Rule::nullable()
                // Card UIN should be unique
                ->unique('cards', Card::UIN, function (Unique $rule) use ($card) {
                    if ($card->exists) {
                        $rule->whereNot(Card::ID, $card->id);
                    }

                    return $rule->whereNull(Card::DELETED_AT);
                }),
            Card::ACTIVE => Rule::required()->boolean(),
            Card::SYNCHRONIZED_AT => Rule::nullable()->date(),
        ];
    }

    /**
     * Stores new card.
     *
     * @param CardData $cardData Card details to create
     *
     * @return Card
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(CardData $cardData): Card
    {
        Log::debug("Create card with card number [{$cardData->card_number}] attempt");

        $card = new Card($cardData->toArray());

        Validator::validate($cardData->toArray(), $this->getCardValidationRules($card));

        $this->save($card);

        Log::debug("Card [{$card->id}] created");

        return $card;
    }

    /**
     * Updates card details.
     *
     * @param Card $card Card to update
     * @param CardData $cardData Card new details
     *
     * @return Card
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws Throwable
     */
    public function update(Card $card, CardData $cardData): Card
    {
        Log::debug("Update card [{$card->id}] attempt");

        // Fill possible empty data
        if (!$card->uin) {
            $card->uin = $cardData->uin;
        }
        if (!$card->card_type_id) {
            $card->card_type_id = $cardData->card_type_id;
        }

        $card->active = $cardData->active;
        $card->synchronized_at = $cardData->synchronized_at;

        if ($card->card_number !== $cardData->card_number ||
            $card->card_type_id !== $cardData->card_type_id ||
            $card->uin !== $cardData->uin
        ) {
            throw new CardReassignException($card);
        }

        Validator::validate($cardData->toArray(), $this->getCardValidationRules($card));

        $this->save($card);

        Log::debug("Card [{$card->id}] updated");

        return $card;
    }

    /**
     * Saves card details with respect to possible empty data.
     *
     * @param Card $card Card to save
     *
     * @return Card
     *
     * @throws RepositoryException
     */
    private function save(Card $card): Card
    {
        if (!$card->uin || !$card->card_type_id) {
            Log::notice('Card details not completely filled. Drop activation sign');
            // Drop synchronization and card activation sync
            $card->synchronized_at = null;
            $card->active = false;
        }

        $this->getRepository()->save($card);

        return $card;
    }

    /**
     * Safely creates card with card number only.
     *
     * @param int $cardNumber Card number to create fallback card with
     *
     * @return Card
     *
     * @throws RepositoryException
     */
    public function fallbackCreate(int $cardNumber): Card
    {
        Log::warning("Fallback card with number [$cardNumber] create attempt");

        $card = new Card([Card::CARD_NUMBER => $cardNumber, Card::ACTIVE => false]);

        $this->getRepository()->create($card);

        Log::warning("Fallback card with number [$cardNumber] created with key [$card->id]");

        return $card;
    }

    /**
     * Safely updates card to drop synchronization sign.
     *
     * @param Card $card Card to safely update
     *
     * @return Card
     *
     * @throws RepositoryException
     */
    public function fallbackUpdate(Card $card): Card
    {
        Log::warning("Fallback card [{$card->id}] update attempt");

        $card->refresh();

        $card->synchronized_at = null;

        $this->getRepository()->save($card);

        Log::warning("Fallback card [$card->id] updated");

        return $card;
    }
}
