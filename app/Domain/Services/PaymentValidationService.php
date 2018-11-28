<?php

namespace App\Domain\Services;

use App\Domain\EntitiesServices\TariffPeriodEntityService;
use App\Domain\Exceptions\Constraint\Payment\InvalidPaymentAmountException;
use App\Domain\Exceptions\Constraint\Payment\MissedPaymentException;
use App\Domain\Exceptions\Constraint\Payment\UnneededPaymentException;
use App\Domain\Exceptions\Integrity\NoTariffFareForDateException;
use App\Domain\Exceptions\Integrity\NoTariffPeriodForDateException;
use App\Domain\Exceptions\Integrity\TooManyTariffFaresForDateException;
use App\Domain\Exceptions\Integrity\TooManyTariffPeriodsForDateException;
use App\Models\Card;
use App\Models\Tariff;
use Carbon\Carbon;
use Saritasa\Exceptions\ConfigurationException;

/**
 * Payments service that can validate payment amount.
 */
class PaymentValidationService
{
    /**
     * Cards business logic service.
     *
     * @var CardService
     */
    private $cardService;

    /**
     * Tariff periods entity service.
     *
     * @var TariffPeriodEntityService
     */
    private $tariffPeriodEntityService;

    /**
     * Tariff fares entity service.
     *
     * @var TariffFareService
     */
    private $tariffFareService;

    /**
     * Payments service that can validate payment amount.
     *
     * @param CardService $cardService Cards business logic service
     * @param TariffPeriodEntityService $tariffPeriodEntityService Tariff periods entity service
     * @param TariffFareService $tariffFareService Tariff fares service
     */
    public function __construct(
        CardService $cardService,
        TariffPeriodEntityService $tariffPeriodEntityService,
        TariffFareService $tariffFareService
    ) {
        $this->cardService = $cardService;
        $this->tariffPeriodEntityService = $tariffPeriodEntityService;
        $this->tariffFareService = $tariffFareService;
    }

    /**
     * Returns whether card owner should pay for bus trip or not. Zero but not null payment also means that should pay.
     *
     * @param Card $card Card type to check
     *
     * @return boolean
     *
     * @throws ConfigurationException
     */
    public function shouldPay(Card $card): bool
    {
        return $this->cardService->isPassengerCard($card);
    }

    /**
     * Validates provided payment.
     *
     * @param Card $card Card that provides payment
     * @param Tariff|null $tariff Used for payment tariff
     * @param int|null $amount Provided payment amount
     * @param Carbon $date Date of payment
     *
     * @throws ConfigurationException
     * @throws NoTariffFareForDateException
     * @throws NoTariffPeriodForDateException
     * @throws TooManyTariffFaresForDateException
     * @throws TooManyTariffPeriodsForDateException
     */
    public function validatePaymentAmount(Card $card, ?Tariff $tariff, ?int $amount, Carbon $date): void
    {
        $shouldPay = $this->shouldPay($card);
        $paymentProvided = $tariff || !is_null($amount);

        if (!$shouldPay && $paymentProvided) {
            throw new UnneededPaymentException($card, $date);
        }

        if (!$shouldPay && !$paymentProvided) {
            return;
        }

        if ($shouldPay && (!$tariff || is_null($amount))) {
            throw new MissedPaymentException($card, $date);
        }

        $tariffFare = $this->tariffFareService->getForOrFail($card->cardType, $tariff, $date);

        if ($tariffFare->amount !== $amount) {
            throw new InvalidPaymentAmountException($card, $tariff, $amount, $tariffFare, $date);
        }
    }
}
