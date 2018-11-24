<?php

namespace App\Domain\EntitiesServices;

use App\Domain\Exceptions\Integrity\NoTariffPeriodForDateException;
use App\Domain\Exceptions\Integrity\TooManyTariffPeriodsForDateException;
use App\Extensions\ActivityPeriod\ActivityPeriodAssistant;
use App\Extensions\EntityService;
use App\Models\TariffPeriod;
use Carbon\Carbon;

/**
 * TariffPeriod entity service.
 */
class TariffPeriodEntityService extends EntityService
{
    /**
     * Returns tariff period that was active at passed date.
     *
     * @param Carbon $date Date to find tariff period
     *
     * @return TariffPeriod|null
     *
     * @throws TooManyTariffPeriodsForDateException
     */
    public function getForDate(Carbon $date): ?TariffPeriod
    {
        $tariffPeriods = $this->getRepository()->getWith(
            [],
            [],
            [
                [ActivityPeriodAssistant::ACTIVE_FROM, '<=', $date],
                [
                    [
                        [ActivityPeriodAssistant::ACTIVE_TO, '=', null, 'or'],
                        [ActivityPeriodAssistant::ACTIVE_TO, '>=', $date, 'or'],
                    ],
                ],
            ]
        );

        if ($tariffPeriods->count() > 1) {
            throw new TooManyTariffPeriodsForDateException($date, $tariffPeriods);
        }

        if ($tariffPeriods->count() === 1) {
            return $tariffPeriods->first();
        }

        return null;
    }

    /**
     * Returns tariff period that is active now.
     *
     * @return TariffPeriod|null
     *
     * @throws TooManyTariffPeriodsForDateException
     * @throws NoTariffPeriodForDateException
     */
    public function getCurrent(): TariffPeriod
    {
        $now = Carbon::now();

        $tariffPeriod = $this->getForDate($now);

        if (!$tariffPeriod) {
            throw new NoTariffPeriodForDateException($now);
        }

        return $tariffPeriod;
    }
}
