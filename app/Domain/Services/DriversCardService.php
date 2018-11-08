<?php

namespace App\Domain\Services;

use App\Domain\Dto\DriversCardData;
use App\Domain\Exceptions\Constraint\DriverCardExistsException;
use App\Domain\Exceptions\Integrity\TooManyDriverCardsException;
use App\Extensions\EntityService;
use App\Models\Card;
use App\Models\Driver;
use App\Models\DriversCard;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\Laravel\Validation\DateRuleSet;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;
use Saritasa\Laravel\Validation\RuleSet;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Validator;

/**
 * Cards to drivers assignments business-logic service.
 */
class DriversCardService extends EntityService
{
    /**
     * Returns validation rule to store or update card to driver assignment.
     *
     * @param DriversCard $driversCard Card to driver assignment to build rules for
     *
     * @return string[]|GenericRuleSet[]
     */
    protected function getDriverValidationRules(DriversCard $driversCard): array
    {
        return [
            DriversCard::ACTIVE_FROM => Rule::required()->date()
                ->when($driversCard->active_to, function (RuleSet $rule) {
                    /**
                     * Date rules set.
                     *
                     * @var DateRuleSet $rule
                     */
                    return $rule->before(DriversCard::ACTIVE_TO);
                }),
            DriversCard::ACTIVE_TO => Rule::nullable()->date()
                ->when($driversCard->active_to, function (RuleSet $rule) {
                    /**
                     * Date rules set.
                     *
                     * @var DateRuleSet $rule
                     */
                    return $rule->after(DriversCard::ACTIVE_FROM);
                }),
            DriversCard::CARD_ID => Rule::required()->exists('cards', Card::ID),
            DriversCard::DRIVER_ID => Rule::required()->exists('drivers', Driver::ID),
        ];
    }

    /**
     * Opens new card to driver assignment period.
     *
     * @param Card $card Card to assign driver to
     * @param Driver $driver Driver to assign card to
     * @param Carbon|null $activeFrom Start date of card to driver assignment period
     *
     * @return DriversCard
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws TooManyDriverCardsException
     */
    public function openPeriod(Card $card, Driver $driver, ?Carbon $activeFrom = null): DriversCard
    {
        Log::debug("Create card [{$card->id}] to driver [{$driver->id}] assign attempt");

        $activeFrom = $activeFrom ?? Carbon::now();

        $cardDriverData = new DriversCardData([
            DriversCardData::ACTIVE_FROM => $activeFrom,
            DriversCardData::CARD_ID => $driver->card_id,
            DriversCardData::DRIVER_ID => $driver->id,
        ]);

        $driversCard = new DriversCard($cardDriverData->toArray());

        Validator::validate($cardDriverData->toArray(), $this->getDriverValidationRules($driversCard));

        $cardDriver = $this->getForDriver($driver, $activeFrom);

        if ($cardDriver) {
            throw new DriverCardExistsException($cardDriver);
        }

        $this->getRepository()->create($driversCard);

        Log::debug("Card to driver assignment [{$driversCard->id}] created");

        return $driversCard;
    }

    /**
     * Closes driver to card assignment period.
     *
     * @param DriversCard $driversCard Card to driver assignment period to close
     * @param Carbon|null $activeTo Date of period at which period should be closed
     *
     * @return DriversCard
     *
     * @throws RepositoryException
     * @throws ValidationException
     */
    public function closePeriod(DriversCard $driversCard, ?Carbon $activeTo = null): DriversCard
    {
        Log::debug("Close card to driver assignment period [{$driversCard->id}] attempt");

        $driversCard->active_to = $activeTo ?? Carbon::now();

        Validator::validate($driversCard->toArray(), $this->getDriverValidationRules($driversCard));

        $this->getRepository()->save($driversCard);

        Log::debug("Card to driver assignment [{$driversCard->id}] period closed");

        return $driversCard;
    }

    /**
     * Returns card to driver assignment that was active at passed date.
     *
     * @param Driver $driver Driver to retrieve assignment for
     * @param Carbon|null $date Date to find tariff period
     *
     * @return DriversCard|null
     *
     * @throws TooManyDriverCardsException
     */
    public function getForDriver(Driver $driver, ?Carbon $date = null): ?DriversCard
    {
        $date = $date ?? Carbon::now();

        $cardDrivers = $this->getRepository()->getWith(
            [],
            [],
            [
                [DriversCard::DRIVER_ID, $driver->getKey()],
                [DriversCard::ACTIVE_FROM, '<=', $date],
                [
                    [
                        [DriversCard::ACTIVE_TO, '=', null, 'or'],
                        [DriversCard::ACTIVE_TO, '>=', $date, 'or'],
                    ],
                ],
            ]
        );

        if ($cardDrivers->count() > 1) {
            throw new TooManyDriverCardsException($date, $driver, $cardDrivers);
        }

        if ($cardDrivers->count() === 1) {
            return $cardDrivers->first();
        }

        return null;
    }
}
