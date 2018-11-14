<?php

namespace App\Domain\Services;

use App\Domain\Dto\BusData;
use App\Domain\Exceptions\Constraint\BusDeletionException;
use App\Domain\Exceptions\Constraint\BusReassignException;
use App\Extensions\EntityService;
use App\Models\Bus;
use App\Models\Company;
use App\Models\Route;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Validator;

/**
 * Bus business-logic service.
 */
class BusService extends EntityService
{
    /**
     * Returns validation rule to store or update bus.
     *
     * @param Bus $bus Bus to build rules for
     *
     * @return string[]|GenericRuleSet[]
     */
    protected function getBusValidationRules(Bus $bus): array
    {
        return [
            Bus::COMPANY_ID => Rule::required()->exists('companies', Company::ID)->int(),
            Bus::MODEL_NAME => Rule::required()->string()->max(24),
            Bus::STATE_NUMBER => Rule::required()
                // Bus should have unique state number among all the companies
                ->unique('buses', Bus::STATE_NUMBER, function (Unique $rule) use ($bus) {
                    if ($bus->exists) {
                        $rule->whereNot(Bus::ID, $bus->id);
                    }

                    return $rule->whereNull(Bus::DELETED_AT);
                })
                ->string()
                ->max(10),
            Bus::ROUTE_ID => Rule::nullable()
                // Route should be in the same company with bus
                ->exists('routes', Route::ID, function (Exists $rule) use ($bus) {
                    $rule->where(Route::COMPANY_ID, $bus->company_id);

                    return $rule->whereNull(Route::DELETED_AT);
                }),
        ];
    }

    /**
     * Stores new bus.
     *
     * @param BusData $busData Bus details to create
     *
     * @return Bus
     *
     * @throws RepositoryException
     * @throws ValidationException
     */
    public function store(BusData $busData): Bus
    {
        Log::debug("Create bus with state number [{$busData->state_number}] attempt");

        $bus = new Bus($busData->toArray());

        Validator::validate($busData->toArray(), $this->getBusValidationRules($bus));

        $this->getRepository()->create($bus);

        Log::debug("Bus [{$bus->id}] created");

        return $bus;
    }

    /**
     * Updates bus details.
     *
     * @param Bus $bus Bus to update
     * @param BusData $busData Bus new details
     *
     * @return Bus
     *
     * @throws RepositoryException
     * @throws ValidationException
     */
    public function update(Bus $bus, BusData $busData): Bus
    {
        Log::debug("Update bus [{$bus->id}] attempt");

        if ($bus->company_id !== $busData->company_id) {
            throw new BusReassignException($bus);
        }

        $bus->fill($busData->toArray());

        Validator::validate($busData->toArray(), $this->getBusValidationRules($bus));

        $this->getRepository()->save($bus);

        Log::debug("Bus [{$bus->id}] updated");

        return $bus;
    }

    /**
     * Deletes bus.
     *
     * @param Bus $bus Bus to delete
     *
     * @throws RepositoryException
     */
    public function destroy(Bus $bus): void
    {
        Log::debug("Delete bus [{$bus->id}] attempt");

        if ($bus->drivers->isNotEmpty() || $bus->validators->isNotEmpty()) {
            Log::debug("Bus [{$bus->id}] has related records. Can't delete");

            throw new BusDeletionException($bus);
        }

        $this->getRepository()->delete($bus);

        Log::debug("Bus [{$bus->id}] deleted");
    }
}
