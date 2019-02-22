<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\ValidatorEntityService;
use App\Domain\Enums\Abilities;
use App\Http\Requests\Api\SaveValidatorRequest;
use App\Models\Validator;
use Dingo\Api\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\Transformers\IDataTransformer;
use Throwable;

/**
 * Validators requests API controller.
 */
class ValidatorsApiController extends BaseApiController
{
    /**
     * Validators entity service.
     *
     * @var ValidatorEntityService
     */
    private $validatorService;

    /**
     * Validators requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param ValidatorEntityService $validatorService Validators entity service
     */
    public function __construct(IDataTransformer $transformer, ValidatorEntityService $validatorService)
    {
        parent::__construct($transformer);
        $this->validatorService = $validatorService;
    }

    /**
     * Returns validators list.
     *
     * @return Response
     *
     * @throws InvalidEnumValueException In case of invalid order direction usage
     * @throws AuthorizationException
     */
    public function index(): Response
    {
        $this->authorize(Abilities::GET, new Validator());

        $validators = $this->validatorService->getWith(
            ['bus.company'],
            [],
            [],
            new SortOptions(Validator::SERIAL_NUMBER)
        );

        if ($this->singleCompanyUser()) {
            $validators = $validators->where('bus.company_id', '=', $this->user->company_id);
        }

        return $this->response->collection($validators, $this->transformer);
    }

    /**
     * Updates validator details.
     *
     * @param SaveValidatorRequest $request Request with new validator information
     * @param Validator $validator Validator to update
     *
     * @return Response
     *
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(SaveValidatorRequest $request, Validator $validator): Response
    {
        $this->authorize(Abilities::UPDATE, $validator);

        $this->validatorService->assignBus($validator, $request->getValidatorBusData()->bus_id);

        return $this->response->item($validator, $this->transformer);
    }
}
