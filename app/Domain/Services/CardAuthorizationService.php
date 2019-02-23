<?php

namespace App\Domain\Services;

use App\Domain\EntitiesServices\BusesValidatorEntityService;
use App\Domain\EntitiesServices\DriversCardEntityService;
use App\Domain\EntitiesServices\RouteSheetService;
use App\Domain\EntitiesServices\TransactionEntityService;
use App\Domain\Exceptions\Constraint\CardAuthorization\CardWithoutDriverAuthorizationException;
use App\Domain\Exceptions\Constraint\CardAuthorization\UnassignedValidatorCardAuthorizationException;
use App\Domain\Exceptions\Constraint\CardAuthorization\UnexpectedCardAuthorizationException;
use App\Domain\Exceptions\Constraint\CardAuthorization\WrongBusDriverAuthorizationException;
use App\Domain\Exceptions\Constraint\Payment\InvalidPaymentAmountException;
use App\Domain\Exceptions\Constraint\Payment\MissedPaymentException;
use App\Domain\Exceptions\Constraint\Payment\UnneededPaymentException;
use App\Domain\Exceptions\Integrity\InconsistentRouteSheetStateException;
use App\Domain\Exceptions\Integrity\NoTariffFareForDateException;
use App\Domain\Exceptions\Integrity\NoTariffPeriodForDateException;
use App\Domain\Exceptions\Integrity\TooManyBusRouteSheetsForDateException;
use App\Domain\Exceptions\Integrity\TooManyBusValidatorsException;
use App\Domain\Exceptions\Integrity\TooManyCardDriversException;
use App\Domain\Exceptions\Integrity\TooManyDriverRouteSheetsForDateException;
use App\Domain\Exceptions\Integrity\TooManyTariffFaresForDateException;
use App\Domain\Exceptions\Integrity\TooManyTariffPeriodsForDateException;
use App\Models\Bus;
use App\Models\Driver;
use App\Models\RouteSheet;
use App\Models\Transaction;
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
     * Transaction entity service.
     *
     * @var TransactionEntityService
     */
    private $transactionEntityService;

    /**
     * Card authorization entity service.
     *
     * @param CardService $cardService Cards service
     * @param RouteSheetService $routeSheetService Route sheets service
     * @param BusesValidatorEntityService $busesValidatorEntityService Bus to validator assignment entity service
     * @param DriversCardEntityService $driversCardEntityService Card to driver assignment entity service
     * @param PaymentValidationService $paymentValidationService Payment validation service
     * @param TransactionEntityService $transactionEntityService Transaction entity service
     */
    public function __construct(
        CardService $cardService,
        RouteSheetService $routeSheetService,
        BusesValidatorEntityService $busesValidatorEntityService,
        DriversCardEntityService $driversCardEntityService,
        PaymentValidationService $paymentValidationService,
        TransactionEntityService $transactionEntityService
    ) {
        $this->routeSheetService = $routeSheetService;
        $this->cardService = $cardService;
        $this->busesValidatorEntityService = $busesValidatorEntityService;
        $this->paymentValidationService = $paymentValidationService;
        $this->driversCardEntityService = $driversCardEntityService;
        $this->transactionEntityService = $transactionEntityService;
    }

    /**
     * Returns bus where card authorization was performed.
     *
     * @param Transaction $cardTransaction Card on bus authorization details
     *
     * @return Bus
     *
     * @throws TooManyBusValidatorsException
     * @throws UnassignedValidatorCardAuthorizationException
     */
    protected function getValidBus(Transaction $cardTransaction): Bus
    {
        $busValidator = $this->busesValidatorEntityService->getForValidator(
            $cardTransaction->validator,
            $cardTransaction->authorized_at
        );

        if (!$busValidator) {
            throw new UnassignedValidatorCardAuthorizationException(
                $cardTransaction->validator,
                $cardTransaction->authorized_at
            );
        }

        return $busValidator->bus;
    }

    /**
     * Returns driver that owned authorized card.
     *
     * @param Transaction $cardTransaction Card on bus authorization details
     *
     * @return Driver
     *
     * @throws TooManyCardDriversException
     * @throws CardWithoutDriverAuthorizationException
     */
    protected function getValidDriver(Transaction $cardTransaction): Driver
    {
        $driverCard = $this->driversCardEntityService->getForCard(
            $cardTransaction->card,
            $cardTransaction->authorized_at
        );

        if (!$driverCard) {
            throw new CardWithoutDriverAuthorizationException(
                $cardTransaction->card,
                $cardTransaction->authorized_at
            );
        }

        return $driverCard->driver;
    }

    /**
     * Process card authorization.
     *
     * @param Transaction $transaction Card on validator authorization record
     *
     * @return RouteSheet|null
     *
     * @throws CardWithoutDriverAuthorizationException
     * @throws ConfigurationException
     * @throws InconsistentRouteSheetStateException
     * @throws InvalidEnumValueException
     * @throws NoTariffFareForDateException
     * @throws NoTariffPeriodForDateException
     * @throws RepositoryException
     * @throws TooManyBusRouteSheetsForDateException
     * @throws TooManyBusValidatorsException
     * @throws TooManyCardDriversException
     * @throws TooManyDriverRouteSheetsForDateException
     * @throws TooManyTariffFaresForDateException
     * @throws TooManyTariffPeriodsForDateException
     * @throws UnassignedValidatorCardAuthorizationException
     * @throws UnexpectedCardAuthorizationException
     * @throws ValidationException
     * @throws WrongBusDriverAuthorizationException
     * @throws InvalidPaymentAmountException
     * @throws MissedPaymentException
     * @throws UnneededPaymentException
     */
    public function processCardAuthorization(Transaction $transaction): ?RouteSheet
    {
        $bus = $this->getValidBus($transaction);
        $card = $transaction->card;
        $authorizationDate = $transaction->authorized_at;

        $this->paymentValidationService->validatePaymentAmount(
            $card,
            $transaction->tariff,
            $transaction->amount,
            $authorizationDate
        );

        if ($this->cardService->isIgnorableCard($card)) {
            Log::debug('Ignorable card type authentication skipped', $transaction->toArray());

            $routeSheet = null;
        } elseif ($this->cardService->isDriverCard($card)) {
            Log::debug('Driver card authentication');

            $driver = $this->getValidDriver($transaction);

            $routeSheet = $this->routeSheetService->openForBusAndDriver($bus, $driver, $authorizationDate);
        } elseif ($this->cardService->isPassengerCard($card)) {
            Log::debug('Passenger card authentication');

            $routeSheet = $this->routeSheetService->openForBusAndDriver($bus, null, $authorizationDate);
        } else {
            Log::notice('Unknown card authentication', $card->toArray());

            throw new UnexpectedCardAuthorizationException($card);
        }

        $this->transactionEntityService->assignRouteSheet($transaction, $routeSheet);

        return $routeSheet;
    }
}
