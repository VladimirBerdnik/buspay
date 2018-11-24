<?php

namespace App\Domain\Services;

use App\Domain\Exceptions\Integrity\NoBusForValidatorException;
use App\Domain\Exceptions\Integrity\TooManyBusValidatorsException;
use App\Domain\ICardAuthorization;
use Log;
use Saritasa\Exceptions\ConfigurationException;

/**
 * Card authorization business logic service.
 */
class CardAuthorizationService
{
    /**
     * Route sheets business logic service.
     *
     * @var RouteSheetService
     */
    private $routeSheetService;

    /**
     * Card types business logic service.
     *
     * @var CardTypeService
     */
    private $cardTypeService;

    /**
     * Bus to validator assignment business logic service.
     *
     * @var BusesValidatorService
     */
    private $busesValidatorService;

    /**
     * Card authorization business logic service.
     *
     * @param CardTypeService $cardTypeService Card types business logic service
     * @param RouteSheetService $routeSheetService Route sheets business logic service
     * @param BusesValidatorService $busesValidatorService Bus to validator assignment business logic service
     */
    public function __construct(
        CardTypeService $cardTypeService,
        RouteSheetService $routeSheetService,
        BusesValidatorService $busesValidatorService
    ) {
        $this->routeSheetService = $routeSheetService;
        $this->cardTypeService = $cardTypeService;
        $this->busesValidatorService = $busesValidatorService;
    }

    /**
     * Process card authorization.
     *
     * @param ICardAuthorization $cardAuthorization Card on validator authorization record
     *
     * @throws ConfigurationException
     * @throws TooManyBusValidatorsException
     * @throws NoBusForValidatorException
     */
    public function processCardAuthorization(ICardAuthorization $cardAuthorization): void
    {
        $busValidator = $this->busesValidatorService->getForValidator(
            $cardAuthorization->getValidator(),
            $cardAuthorization->getDate()
        );

        if (!$busValidator) {
            throw new NoBusForValidatorException($cardAuthorization->getValidator());
        }

        $bus = $busValidator->bus;
        $authorizedCard = $cardAuthorization->getCard();

        if ($this->cardTypeService->isDriverCard($authorizedCard)) {
            Log::debug('Driver card authentication');
            // Ensure payment not taken
            // Find driver for card at date
            // Check that driver authorized on his company bus
            // Find route sheet, close if exists and open new one
        } elseif ($this->cardTypeService->isPassengerCard($authorizedCard)) {
            Log::debug('Passenger card authentication');
            // Check payment amount for tariff, date and card type
            // Find route sheet for bus or create new
        } else {
            Log::debug('Service or unknown card type authentication');
            // Ensure payment not taken
            // some unexpected card authorization detected, need to warn and ignore
        }
    }
}
