<?php

namespace App\Domain\Services;

use App\Models\Card;
use App\Models\CardType;
use Saritasa\Exceptions\ConfigurationException;

/**
 * Cards business logic service.
 */
class CardService
{
    /**
     * Card types business logic service.
     *
     * @var CardTypeService
     */
    private $cardTypeService;

    /**
     * Cards business logic service.
     *
     * @param CardTypeService $cardTypeService Card types business logic service
     */
    public function __construct(CardTypeService $cardTypeService)
    {
        $this->cardTypeService = $cardTypeService;
    }

    /**
     * Returns whether passed card belongs to passengers cards or not.
     *
     * @param Card $card Card to check
     *
     * @return boolean
     *
     * @throws ConfigurationException
     */
    public function isPassengerCard(Card $card): bool
    {
        return $this->cardTypeService->getPassengersCardTypes()->contains(CardType::ID, $card->card_type_id);
    }

    /**
     * Returns whether passed card belongs to driver card or not.
     *
     * @param Card $card Card to check
     *
     * @return boolean
     *
     * @throws ConfigurationException
     */
    public function isDriverCard(Card $card): bool
    {
        return $this->cardTypeService->getDriverCardType()->id === $card->card_type_id;
    }

    /**
     * Returns whether passed card belongs to ignorable card type or not.
     *
     * @param Card $card Card to check
     *
     * @return boolean
     */
    public function isIgnorableCard(Card $card): bool
    {
        return $this->cardTypeService->ignorableCardType($card->card_type_id);
    }
}
