<?php

namespace App\Domain\EntitiesServices;

use App\Domain\Exceptions\Constraint\ActivityPeriodExistsException;
use App\Domain\Exceptions\Constraint\DriverCardExistsException;
use App\Domain\Exceptions\Integrity\TooManyActivityPeriodsException;
use App\Domain\Exceptions\Integrity\TooManyDriverCardsException;
use App\Extensions\ActivityPeriod\IActivityPeriod;
use App\Models\Card;
use App\Models\Driver;
use App\Models\DriversCard;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;

/**
 * Cards to drivers assignments business-logic service.
 */
class DriversCardEntityService extends ModelRelationActivityPeriodService
{
    /**
     * Opens new card to driver assignment period.
     *
     * @param Driver $driver Driver to assign card to
     * @param Card $card Card to assign driver to
     * @param Carbon|null $activeFrom Start date of card to driver assignment period
     *
     * @return DriversCard|IActivityPeriod
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws TooManyDriverCardsException
     */
    public function openDriverCardPeriod(Driver $driver, Card $card, ?Carbon $activeFrom = null): DriversCard
    {
        try {
            return $driversCard = $this->openPeriod($driver, $card, $activeFrom);
        } catch (TooManyActivityPeriodsException $e) {
            throw new TooManyDriverCardsException($e->getDate(), $driver, $e->getActivityPeriods());
        } catch (ActivityPeriodExistsException $e) {
            throw new DriverCardExistsException($e->getActivityPeriod());
        }
    }

    /**
     * Closes driver to card assignment period.
     *
     * @param DriversCard $driversCard Card to driver assignment period to close
     * @param Carbon|null $activeTo Date of period at which period should be closed
     *
     * @return DriversCard|IActivityPeriod
     *
     * @throws RepositoryException
     * @throws ValidationException
     */
    public function closeDriverCardPeriod(DriversCard $driversCard, ?Carbon $activeTo = null): DriversCard
    {
        return $this->closePeriod($driversCard, $activeTo);
    }

    /**
     * Returns card to driver assignment that was active at passed date.
     *
     * @param Driver $driver Driver to retrieve assignment for
     * @param Carbon|null $date Date to find tariff period
     *
     * @return DriversCard|IActivityPeriod|null
     *
     * @throws TooManyDriverCardsException
     */
    public function getForDriver(Driver $driver, ?Carbon $date = null): ?DriversCard
    {
        try {
            return $this->getPeriodFor($driver, $date);
        } catch (TooManyActivityPeriodsException $e) {
            throw new TooManyDriverCardsException($date, $driver, $e->getActivityPeriods());
        }
    }
}
