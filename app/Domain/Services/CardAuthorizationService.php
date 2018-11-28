<?php

namespace App\Domain\Services;

use App\Domain\EntitiesServices\BusesValidatorEntityService;
use App\Domain\EntitiesServices\DriversCardEntityService;
use App\Domain\EntitiesServices\RouteSheetService;
use App\Domain\Exceptions\Constraint\CardAuthorization\CardWithoutDriverAuthorizationException;
use App\Domain\Exceptions\Constraint\CardAuthorization\UnassignedValidatorCardAuthorizationException;
use App\Domain\Exceptions\Constraint\CardAuthorization\UnexpectedCardAuthorizationException;
use App\Domain\Exceptions\Integrity\InconsistentRouteSheetStateException;
use App\Domain\Exceptions\Integrity\NoTariffFareForDateException;
use App\Domain\Exceptions\Integrity\NoTariffPeriodForDateException;
use App\Domain\Exceptions\Integrity\TooManyBusRouteSheetsForDateException;
use App\Domain\Exceptions\Integrity\TooManyBusValidatorsException;
use App\Domain\Exceptions\Integrity\TooManyCardDriversException;
use App\Domain\Exceptions\Integrity\TooManyDriverRouteSheetsForDateException;
use App\Domain\Exceptions\Integrity\TooManyTariffFaresForDateException;
use App\Domain\Exceptions\Integrity\TooManyTariffPeriodsForDateException;
use App\Domain\ICardAuthorization;
use App\Models\Bus;
use App\Models\Driver;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\Exceptions\ConfigurationException;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;

/**
 * Card authorization entity service.
 */
class CardAuthorizationService
{
    /**
     * Route sheets service.
     *
     * @var RouteSheetService
     */
    private $routeSheetService;

    /**
     * Cards service.
     *
     * @var CardService
     */
    private $cardService;

    /**
     * Bus to validator assignment entity service.
     *
     * @var BusesValidatorEntityService
     */
    private $busesValidatorEntityService;

    /**
     * Payment validation service.
     *
     * @var PaymentValidationService
     */
    private $paymentValidationService;

    /**
     * Card to driver assignment entity service.
     *
     * @var DriversCardEntityService
     */
    private $driversCardEntityService;

    /**
     * Card authorization entity service.
     *
     * @param CardService $cardService Cards service
     * @param RouteSheetService $routeSheetService Route sheets service
     * @param BusesValidatorEntityService $busesValidatorEntityService Bus to validator assignment entity service
     * @param DriversCardEntityService $driversCardEntityService Card to driver assignment entity service
     * @param PaymentValidationService $paymentValidationService Payment validation service
     */
    public function __construct(
        CardService $cardService,
        RouteSheetService $routeSheetService,
        BusesValidatorEntityService $busesValidatorEntityService,
        DriversCardEntityService $driversCardEntityService,
        PaymentValidationService $paymentValidationService
    ) {
        $this->routeSheetService = $routeSheetService;
        $this->cardService = $cardService;
        $this->busesValidatorEntityService = $busesValidatorEntityService;
        $this->paymentValidationService = $paymentValidationService;
        $this->driversCardEntityService = $driversCardEntityService;
    }

    /**
     * Returns bus where card authorization was performed.
     *
     * @param ICardAuthorization $cardAuthorization Card on bus authorization details
     *
     * @return Bus
     *
     * @throws TooManyBusValidatorsException
     */
    protected function getValidBus(ICardAuthorization $cardAuthorization): Bus
    {
        $busValidator = $this->busesValidatorEntityService->getForValidator(
            $cardAuthorization->getValidator(),
            $cardAuthorization->getDate()
        );

        if (!$busValidator) {
            throw new UnassignedValidatorCardAuthorizationException(
                $cardAuthorization->getValidator(),
                $cardAuthorization->getDate()
            );
        }

        return $busValidator->bus;
    }

    /**
     * Returns driver that owned authorized card.
     *
     * @param ICardAuthorization $cardAuthorization Card on bus authorization details
     *
     * @return Driver
     *
     * @throws TooManyCardDriversException
     */
    protected function getValidDriver(ICardAuthorization $cardAuthorization): Driver
    {
        $driverCard = $this->driversCardEntityService->getForCard(
            $cardAuthorization->getCard(),
            $cardAuthorization->getDate()
        );

        if (!$driverCard) {
            throw new CardWithoutDriverAuthorizationException(
                $cardAuthorization->getCard(),
                $cardAuthorization->getDate()
            );
        }

        return $driverCard->driver;
    }

    /**
     * Process card authorization.
     *
     * @param ICardAuthorization $cardAuthorization Card on validator authorization record
     *
     * @throws ConfigurationException
     * @throws InconsistentRouteSheetStateException
     * @throws NoTariffFareForDateException
     * @throws NoTariffPeriodForDateException
     * @throws RepositoryException
     * @throws TooManyBusRouteSheetsForDateException
     * @throws TooManyBusValidatorsException
     * @throws TooManyCardDriversException
     * @throws TooManyDriverRouteSheetsForDateException
     * @throws TooManyTariffFaresForDateException
     * @throws TooManyTariffPeriodsForDateException
     * @throws ValidationException
     * @throws InvalidEnumValueException
     */
    public function processCardAuthorization(ICardAuthorization $cardAuthorization): void
    {
        $bus = $this->getValidBus($cardAuthorization);
        $card = $cardAuthorization->getCard();
        $authorizationDate = $cardAuthorization->getDate();

        $this->paymentValidationService->validatePaymentAmount(
            $card,
            $cardAuthorization->getTariff(),
            $cardAuthorization->getPaymentAmount(),
            $authorizationDate
        );

        if ($this->cardService->isIgnorableCard($card)) {
            Log::debug('Ignorable card type authorization skipped', $cardAuthorization->toArray());

            return;
        } elseif ($this->cardService->isDriverCard($card)) {
            $driver = $this->getValidDriver($cardAuthorization);

            $this->routeSheetService->openForBusAndDriver($bus, $driver, $authorizationDate);
        } elseif ($this->cardService->isPassengerCard($card)) {
            Log::debug('Passenger card authentication');
            $this->routeSheetService->openForBusAndDriver($bus, null, $authorizationDate);
        } else {
            Log::notice('Unknown card authentication', $card->toArray());
            throw new UnexpectedCardAuthorizationException($card);
        }
    }
}
