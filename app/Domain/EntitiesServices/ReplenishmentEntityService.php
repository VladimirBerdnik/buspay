<?php

namespace App\Domain\EntitiesServices;

use App\Domain\Dto\ReplenishmentData;
use App\Extensions\EntityService;
use App\Models\Card;
use App\Models\Replenishment;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Throwable;
use Validator;

/**
 * Card replenishment entity service.
 */
class ReplenishmentEntityService extends EntityService
{
    /**
     * Returns validation rule to store replenishment.
     *
     * @return string[]|GenericRuleSet[]
     */
    protected function getReplenishmentValidationRules(): array
    {
        return [
            Replenishment::CARD_ID => Rule::required()->exists('cards', Card::ID),
            Replenishment::EXTERNAL_ID => Rule::required()
                // Replenishment should have unique external identifier to avoid double storing attempt
                ->unique('replenishments', Replenishment::EXTERNAL_ID),
            Replenishment::AMOUNT => Rule::required()->numeric()->min(0.001),
            Replenishment::REPLENISHED_AT => Rule::required()->date()->beforeOrEqual(Carbon::today()->endOfDay()),
        ];
    }

    /**
     * Creates replenishment details.
     *
     * @param ReplenishmentData $replenishmentData New replenishment details
     *
     * @return Replenishment
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(ReplenishmentData $replenishmentData): Replenishment
    {
        Log::debug("Create replenishment with external ID [{$replenishmentData->external_id}] attempt");

        $replenishment = new Replenishment($replenishmentData->toArray());

        Validator::validate($replenishmentData->toArray(), $this->getReplenishmentValidationRules());

        $this->getRepository()->save($replenishment);

        Log::debug("Replenishment [{$replenishment->id}] created");

        return $replenishment;
    }
}
