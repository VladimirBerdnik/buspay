<?php

namespace App\Domain\Services;

use App\Domain\Exceptions\Constraint\ActivityPeriodExistsException;
use App\Domain\Exceptions\Constraint\BusValidatorExistsException;
use App\Domain\Exceptions\Integrity\TooManyActivityPeriodsException;
use App\Domain\Exceptions\Integrity\TooManyBusValidatorsException;
use App\Extensions\ActivityPeriod\IActivityPeriod;
use App\Models\Bus;
use App\Models\BusesValidator;
use App\Models\Validator;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;

/**
 * BusesValidator business-logic service.
 */
class BusesValidatorService extends ModelRelationActivityPeriodService
{
    /**
     * Opens new bus to validator assignment period.
     *
     * @param Validator $validator Validator to assign bus to
     * @param Bus $bus Bus to assign validator to
     * @param Carbon|null $activeFrom Start date of bus to validator assignment period
     *
     * @return BusesValidator|IActivityPeriod
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws TooManyBusValidatorsException
     */
    public function openBusValidatorPeriod(Validator $validator, Bus $bus, ?Carbon $activeFrom = null): BusesValidator
    {
        try {
            return $this->openPeriod($validator, $bus, $activeFrom);
        } catch (TooManyActivityPeriodsException $e) {
            throw new TooManyBusValidatorsException($e->getDate(), $validator, $e->getActivityPeriods());
        } catch (ActivityPeriodExistsException $e) {
            throw new BusValidatorExistsException($e->getActivityPeriod());
        }
    }

    /**
     * Closes validator to bus assignment period.
     *
     * @param BusesValidator $busesValidator Bus to validator assignment period to close
     * @param Carbon|null $activeTo Date of period at which period should be closed
     *
     * @return BusesValidator|IActivityPeriod
     *
     * @throws RepositoryException
     * @throws ValidationException
     */
    public function closeBusValidatorPeriod(BusesValidator $busesValidator, ?Carbon $activeTo = null): BusesValidator
    {
        return $this->closePeriod($busesValidator, $activeTo);
    }

    /**
     * Returns bus to validator assignment that was active at passed date.
     *
     * @param Validator $validator Validator to retrieve assignment for
     * @param Carbon|null $date Date to find tariff period
     *
     * @return BusesValidator|IActivityPeriod|null
     *
     * @throws TooManyBusValidatorsException
     */
    public function getForValidator(Validator $validator, ?Carbon $date = null): ?BusesValidator
    {
        try {
            return $this->getPeriodFor($validator, $date);
        } catch (TooManyActivityPeriodsException $e) {
            throw new TooManyBusValidatorsException($date, $validator, $e->getActivityPeriods());
        }
    }
}
