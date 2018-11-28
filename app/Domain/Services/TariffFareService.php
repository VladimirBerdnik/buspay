<?php

namespace App\Domain\Services;

use App\Domain\EntitiesServices\TariffFareEntityService;
use App\Domain\EntitiesServices\TariffPeriodEntityService;
use App\Domain\Exceptions\Integrity\NoTariffFareForDateException;
use App\Domain\Exceptions\Integrity\NoTariffPeriodForDateException;
use App\Domain\Exceptions\Integrity\TooManyTariffFaresForDateException;
use App\Domain\Exceptions\Integrity\TooManyTariffPeriodsForDateException;
use App\Models\CardType;
use App\Models\Tariff;
use App\Models\TariffFare;
use Carbon\Carbon;

/**
 * Tariff fares business logic service.
 */
class TariffFareService
{
    /**
     * Tariff periods entity service.
     *
     * @var TariffPeriodEntityService
     */
    private $tariffPeriodEntityService;

    /**
     * Tariff periods entity service.
     *
     * @var TariffFareEntityService
     */
    private $tariffFareEntityService;

    /**
     * Tariff fares business logic service.
     *
     * @param TariffPeriodEntityService $tariffPeriodEntityService Tariff periods entity service
     * @param TariffFareEntityService $tariffFareEntityService Tariff periods entity service
     */
    public function __construct(
        TariffPeriodEntityService $tariffPeriodEntityService,
        TariffFareEntityService $tariffFareEntityService
    ) {
        $this->tariffPeriodEntityService = $tariffPeriodEntityService;
        $this->tariffFareEntityService = $tariffFareEntityService;
    }

    /**
     * Returns tariff fare for given parameters.
     *
     * @param CardType $cardType Card type to retrieve tariff fare for
     * @param Tariff $tariff Tariff to retrieve tariff fare for
     * @param Carbon $date Date to retrieve tariff fare for
     *
     * @return TariffFare|null
     *
     * @throws NoTariffPeriodForDateException
     * @throws TooManyTariffPeriodsForDateException
     * @throws TooManyTariffFaresForDateException
     */
    public function getFor(CardType $cardType, Tariff $tariff, Carbon $date): ?TariffFare
    {
        $tariffPeriod = $this->tariffPeriodEntityService->getForDateOrFail($date);

        $tariffFares = $this->tariffFareEntityService->getWhere([
            TariffFare::TARIFF_PERIOD_ID => $tariffPeriod->getKey(),
            TariffFare::TARIFF_ID => $tariff->getKey(),
            TariffFare::CARD_TYPE_ID => $cardType->getKey(),
        ]);

        if ($tariffFares->count() > 1) {
            throw new TooManyTariffFaresForDateException($cardType, $tariff, $date, $tariffFares);
        }

        if ($tariffFares->count() === 1) {
            return $tariffFares->first();
        }

        return null;
    }

    /**
     * Returns tariff fare for given parameters. Throws an exception when no tariff fare for given parameters found.
     *
     * @param CardType $cardType Card type to retrieve tariff fare for
     * @param Tariff $tariff Tariff to retrieve tariff fare for
     * @param Carbon $date Date to retrieve tariff fare for
     *
     * @return TariffFare|null
     *
     * @throws NoTariffPeriodForDateException
     * @throws TooManyTariffPeriodsForDateException
     * @throws TooManyTariffFaresForDateException
     * @throws NoTariffFareForDateException
     */
    public function getForOrFail(CardType $cardType, Tariff $tariff, Carbon $date): TariffFare
    {
        $tariffFare = $this->getFor($cardType, $tariff, $date);

        if (!$tariffFare) {
            throw new NoTariffFareForDateException($cardType, $tariff, $date);
        }

        return $tariffFare;
    }
}
