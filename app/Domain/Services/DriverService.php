<?php

namespace App\Domain\Services;

use App\Domain\Dto\DriverData;
use App\Domain\Enums\CardTypesIdentifiers;
use App\Domain\Exceptions\Constraint\DriverDeletionException;
use App\Domain\Exceptions\Constraint\DriverReassignException;
use App\Domain\Exceptions\Integrity\NoDriverForCardException;
use App\Domain\Exceptions\Integrity\TooManyDriverCardsException;
use App\Domain\Exceptions\Integrity\UnexpectedCardForDriverException;
use App\Extensions\EntityService;
use App\Models\Bus;
use App\Models\Card;
use App\Models\Company;
use App\Models\Driver;
use Carbon\Carbon;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;
use Saritasa\LaravelRepositories\Contracts\IRepository;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Throwable;
use Validator;

/**
 * Driver business-logic service.
 */
class DriverService extends EntityService
{
    /**
     * Company to driver assignments business-logic.
     *
     * @var DriversCardService
     */
    private $driversCardService;

    /**
     * Driver business-logic service.
     *
     * @param ConnectionInterface $connection Data storage connection
     * @param IRepository $repository Handled entities storage
     * @param DriversCardService $driversCardService Company to driver assignments business-logic
     */
    public function __construct(
        ConnectionInterface $connection,
        IRepository $repository,
        DriversCardService $driversCardService
    ) {
        parent::__construct($connection, $repository);
        $this->driversCardService = $driversCardService;
    }

    /**
     * Returns validation rule to store or update driver.
     *
     * @param Driver $driver Driver to build rules for
     *
     * @return string[]|GenericRuleSet[]
     */
    protected function getDriverValidationRules(Driver $driver): array
    {
        return [
            Driver::COMPANY_ID => Rule::required()->exists('companies', Company::ID),
            Driver::FULL_NAME => Rule::required()
                // Driver full name should be unique in company
                ->unique('drivers', Driver::FULL_NAME, function (Unique $rule) use ($driver) {
                    if ($driver->exists) {
                        $rule->whereNot(Driver::ID, $driver->id);
                    }

                    return $rule->where(Driver::COMPANY_ID, $driver->company_id)
                        ->whereNull(Driver::DELETED_AT);
                })->string()->max(96),
            Driver::CARD_ID => Rule::nullable()
                // Card should exists and be of the driver card type
                ->exists('cards', Card::ID, function (Exists $rule) use ($driver) {
                    $rule->where(Card::CARD_TYPE_ID, CardTypesIdentifiers::DRIVER)
                        ->where(Card::ACTIVE, true)
                        ->whereNotNull(Card::SYNCHRONIZED_AT);

                    return $rule->whereNull(Card::DELETED_AT);
                })
                // Card should be used only once
                ->unique('drivers', Driver::CARD_ID, function (Unique $rule) use ($driver) {
                    if ($driver->exists) {
                        $rule->whereNot(Driver::ID, $driver->id);
                    }

                    return $rule->whereNull(Driver::DELETED_AT);
                }),
            Driver::BUS_ID => Rule::nullable()
                // Bus should be in the same company with driver
                ->exists('buses', Bus::ID, function (Exists $rule) use ($driver) {
                    $rule->where(Bus::COMPANY_ID, $driver->company_id);

                    return $rule->whereNull(Bus::DELETED_AT);
                }),
        ];
    }

    /**
     * Stores new driver.
     *
     * @param DriverData $driverData Driver details to create
     *
     * @return Driver
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(DriverData $driverData): Driver
    {
        Log::debug("Create driver with name [{$driverData->full_name}] attempt");

        $driver = new Driver($driverData->toArray());

        Validator::validate($driverData->toArray(), $this->getDriverValidationRules($driver));

        $this->handleTransaction(function () use ($driver): void {
            $this->getRepository()->create($driver);

            if ($driver->card_id) {
                $this->driversCardService->openDriverCardPeriod($driver, $driver->card);
            }
        });

        Log::debug("Driver [{$driver->id}] created");

        return $driver;
    }

    /**
     * Updates driver details.
     *
     * @param Driver $driver Driver to update
     * @param DriverData $driverData Driver new details
     *
     * @return Driver
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws Throwable
     */
    public function update(Driver $driver, DriverData $driverData): Driver
    {
        Log::debug("Update driver [{$driver->id}] attempt");

        if ($driver->company_id !== $driverData->company_id) {
            throw new DriverReassignException($driver);
        }

        $cardChanged = $driver->card_id !== $driverData->card_id;
        $previouslyAssignedCard = $driver->card;
        $assignedCardId = $driverData->card_id;

        $driver->fill($driverData->toArray());

        Validator::validate($driverData->toArray(), $this->getDriverValidationRules($driver));

        $this->handleTransaction(function () use (
            $previouslyAssignedCard,
            $assignedCardId,
            $driverData,
            $cardChanged,
            $driver
        ): void {
            $date = Carbon::now();

            if ($previouslyAssignedCard && $cardChanged) {
                $this->closeDriverCardPeriod($driver, $previouslyAssignedCard, $date);
            }

            $this->getRepository()->save($driver);
            $driver->load('card');

            if ($cardChanged && $assignedCardId) {
                // Open period for new company
                $this->driversCardService->openDriverCardPeriod($driver, $driver->card, $date->copy()->addSecond());
            }
        });

        Log::debug("Driver [{$driver->id}] updated");

        return $driver;
    }

    /**
     * Detaches card from driver.
     *
     * @param Driver $driver Driver to detach card from
     *
     * @throws Throwable
     */
    public function detachCard(Driver $driver): void
    {
        Log::warning("Going to detach card [{$driver->card_id}] from driver [{$driver->id}]");

        $this->handleTransaction(function () use ($driver): void {
            $this->closeDriverCardPeriod($driver, $driver->card);

            $driver->card_id = null;

            $this->getRepository()->save($driver);
        });

        Log::warning("Card was detached from driver [{$driver->id}]");
    }

    /**
     * Deletes driver.
     *
     * @param Driver $driver Driver to delete
     *
     * @throws RepositoryException
     * @throws Throwable
     */
    public function destroy(Driver $driver): void
    {
        Log::debug("Delete driver [{$driver->id}] attempt");

        if ($driver->card_id) {
            throw new DriverDeletionException($driver);
        }

        $this->getRepository()->delete($driver);

        Log::debug("Driver [{$driver->id}] deleted");
    }

    /**
     * Closes period for existing driver card.
     *
     * @param Driver $driver Driver to close current card activity period for
     * @param Card $expectedCard Expected card for driver activity period
     * @param Carbon|null $date Date to close period at
     *
     * @throws NoDriverForCardException
     * @throws RepositoryException
     * @throws TooManyDriverCardsException
     * @throws UnexpectedCardForDriverException
     * @throws ValidationException
     */
    private function closeDriverCardPeriod(Driver $driver, Card $expectedCard, ?Carbon $date = null): void
    {
        $date = $date ?? Carbon::now();

        $driversCard = $this->driversCardService->getForDriver($driver, $date);

        if (!$driversCard) {
            throw new NoDriverForCardException($driver);
        }

        if ($driversCard->card_id !== $expectedCard->id) {
            throw new UnexpectedCardForDriverException($driversCard, $expectedCard);
        }

        $this->driversCardService->closeDriverCardPeriod($driversCard, $date);
    }
}
