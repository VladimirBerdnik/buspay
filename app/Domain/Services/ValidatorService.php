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
use Illuminate\Validation\Rules\Unique;
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
     * @param Validator $validator Validator to build validation rules for
     *
     * @return string[]|GenericRuleSet[]
     */
    protected function getValidatorValidationRules(Validator $validator): array
    {
        return [
            Validator::SERIAL_NUMBER => Rule::required()
                // Validator should have unique serial number
                ->unique('validators', Validator::SERIAL_NUMBER, function (Unique $rule) use ($validator) {
                    if ($validator->exists) {
                        $rule->whereNot(Validator::ID, $validator->id);
                    }

                    return $rule->whereNull(Bus::DELETED_AT);
                })
                ->string()
                ->max(32),
            Validator::EXTERNAL_ID => Rule::required()
                // Validator should have unique external identifier
                ->unique('validators', Validator::EXTERNAL_ID, function (Unique $rule) use ($validator) {
                    if ($validator->exists) {
                        $rule->whereNot(Validator::ID, $validator->id);
                    }

                    return $rule->whereNull(Bus::DELETED_AT);
                }),
            Validator::MODEL => Rule::required()->string()->max(32),
            Validator::BUS_ID => Rule::nullable()->exists('buses', Bus::ID),
        ];
    }

    /**
     * Assigns or detaches bus from validator.
     *
     * @param Validator $validator Validator to assign bus to
     * @param integer|null $busId Bus identifier to assign. Detaches bus from validator when empty value passed
     *
     * @return Validator
     *
     * @throws Throwable
     * @throws ValidationException
     */
    public function assignBus(Validator $validator, ?int $busId): Validator
    {
        Log::debug("Assign bus [{$busId}] to validator [{$validator->id}] attempt");

        $busChanged = $validator->bus_id !== $busId;
        $previouslyAssignedBus = $validator->bus;
        $assignedBusId = $busId;

        $validator->bus_id = $busId;

        DataValidator::validate($validator->toArray(), $this->getValidatorValidationRules($validator));

        $this->handleTransaction(function () use (
            $previouslyAssignedBus,
            $assignedBusId,
            $busChanged,
            $validator
        ): void {
            $date = Carbon::now();

            if ($previouslyAssignedBus && $busChanged) {
                $this->closeCurrentValidatorPeriod($validator, $previouslyAssignedBus, $date);
            }

            $this->getRepository()->save($validator);
            $validator->load('bus');

            if ($busChanged && $assignedBusId) {
                $this->busesValidatorService->openBusValidatorPeriod(
                    $validator,
                    $validator->bus,
                    $date->copy()->addSecond()
                );
            }
        });

        Log::debug("Bus [{$busId}] was assigned to validator [{$validator->id}]");

        return $validator;
    }

    /**
     * Creates validator details.
     *
     * @param ValidatorData $validatorData New validator details
     *
     * @return Validator
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(ValidatorData $validatorData): Validator
    {
        Log::debug("Create validator with serial number [{$validatorData->serial_number}] attempt");

        $validator = new Validator($validatorData->toArray());

        DataValidator::validate($validatorData->toArray(), $this->getValidatorValidationRules($validator));

        $this->getRepository()->save($validator);

        Log::debug("Validator [{$validator->id}] created");

        return $validator;
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

        $validator->fill($validatorData->toArray());

        DataValidator::validate($validatorData->toArray(), $this->getValidatorValidationRules($validator));

        $this->getRepository()->save($validator);

        Log::debug("Validator [{$validator->id}] updated");

        return $validator;
    }

    /**
     * Closes current bus to validator assignment period.
     *
     * @param Validator $validator Validator for which need to close current bus assignment record
     * @param Bus $expectedBus Expected bus for validator activity period
     * @param Carbon|null $date Date of end of bus with validator activity period record
     *
     * @throws NoBusForValidatorException
     * @throws RepositoryException
     * @throws TooManyBusValidatorsException
     * @throws UnexpectedBusForValidatorException
     * @throws ValidationException
     */
    private function closeCurrentValidatorPeriod(Validator $validator, Bus $expectedBus, ?Carbon $date = null): void
    {
        $busValidator = $this->busesValidatorService->getForValidator($validator, $date);

        if (!$busValidator) {
            throw new NoBusForValidatorException($validator);
        }

        if ($busValidator->bus_id !== $expectedBus->id) {
            throw new UnexpectedBusForValidatorException($busValidator, $expectedBus);
        }

        $this->busesValidatorService->closeBusValidatorPeriod($busValidator, $date);
    }
}
