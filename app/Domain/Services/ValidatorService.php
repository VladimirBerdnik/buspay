<?php

namespace App\Domain\Services;

use App\Domain\Dto\ValidatorData;
use App\Domain\Exceptions\Integrity\NoBusForValidatorException;
use App\Domain\Exceptions\Integrity\TooManyBusValidatorsException;
use App\Domain\Exceptions\Integrity\UnexpectedBusForValidatorException;
use App\Models\Bus;
use App\Models\Validator;
use Carbon\Carbon;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;
use Saritasa\LaravelRepositories\Contracts\IRepository;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Throwable;
use Validator as DataValidator;

/**
 * Validator business-logic service.
 */
class ValidatorService extends ModelRelationActivityPeriodService
{
    /**
     * Bus to validator assignments business-logic.
     *
     * @var BusesValidatorService
     */
    private $busesValidatorService;

    /**
     * Validator business-logic service.
     *
     * @param ConnectionInterface $connection Data storage connection
     * @param IRepository $repository Handled entities storage
     * @param BusesValidatorService $busesValidatorService Bus to validator assignments business-logic
     */
    public function __construct(
        ConnectionInterface $connection,
        IRepository $repository,
        BusesValidatorService $busesValidatorService
    ) {
        parent::__construct($connection, $repository);
        $this->busesValidatorService = $busesValidatorService;
    }

    /**
     * Returns validation rule to store or update validator.
     *
     * @return string[]|GenericRuleSet[]
     */
    protected function getValidatorValidationRules(): array
    {
        return [
            Validator::BUS_ID => Rule::nullable()->exists('buses', Bus::ID),
        ];
    }

    /**
     * Updates validator details.
     *
     * @param Validator $validator Validator to update
     * @param ValidatorData $validatorData Validator new details
     *
     * @return Validator
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws Throwable
     */
    public function update(Validator $validator, ValidatorData $validatorData): Validator
    {
        Log::debug("Update validator [{$validator->id}] attempt");

        DataValidator::validate($validatorData->toArray(), $this->getValidatorValidationRules());

        $busChanged = $validator->bus_id !== $validatorData->bus_id;
        $busWasAssigned = $validator->bus_id;
        $busAssigned = $validatorData->bus_id;

        $this->handleTransaction(function () use (
            $busWasAssigned,
            $busAssigned,
            $validatorData,
            $busChanged,
            $validator
        ): void {
            $date = Carbon::now();

            if ($busWasAssigned && $busChanged) {
                $this->closeCurrentValidatorPeriod($validator, $date);
            }

            $newAttributes = $validatorData->toArray();
            $validator->fill($newAttributes);
            $this->getRepository()->save($validator);

            if ($busChanged && $busAssigned) {
                $this->busesValidatorService->openBusValidatorPeriod(
                    $validator,
                    $validator->bus,
                    $date->copy()->addSecond()
                );
            }
        });

        Log::debug("Validator [{$validator->id}] updated");

        return $validator;
    }

    /**
     * Closes current bus to validator assignment period.
     *
     * @param Validator $validator Validator for which need to close current bus assignment record
     * @param Carbon|null $date Date of end of bus with validator activity period record
     *
     * @throws NoBusForValidatorException
     * @throws RepositoryException
     * @throws TooManyBusValidatorsException
     * @throws UnexpectedBusForValidatorException
     * @throws ValidationException
     */
    private function closeCurrentValidatorPeriod(Validator $validator, ?Carbon $date = null): void
    {
        $busValidator = $this->busesValidatorService->getForValidator($validator, $date);

        if (!$busValidator) {
            throw new NoBusForValidatorException($validator);
        }

        if ($busValidator->bus_id !== $validator->bus_id) {
            throw new UnexpectedBusForValidatorException($busValidator, $validator->bus);
        }

        $this->busesValidatorService->closeBusValidatorPeriod($busValidator, $date);
    }
}
