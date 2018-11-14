<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\Services\ValidatorService;
use App\Http\Requests\Api\SaveValidatorRequest;
use App\Models\Validator;
use Dingo\Api\Http\Response;
use Illuminate\Validation\ValidationException;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Saritasa\Transformers\IDataTransformer;
use Throwable;

/**
 * Validators requests API controller.
 */
class ValidatorsApiController extends BaseApiController
{
    /**
     * Validators business-logic service.
     *
     * @var ValidatorService
     */
    private $validatorService;

    /**
     * Validators requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param ValidatorService $validatorService Validators business logic service
     */
    public function __construct(IDataTransformer $transformer, ValidatorService $validatorService)
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
     */
    public function index(): Response
    {
        return $this->response->collection(
            $this->validatorService->getWith(
                ['bus'],
                [],
                [],
                new SortOptions(Validator::SERIAL_NUMBER)
            ),
            $this->transformer
        );
    }

    /**
     * Updates validator details.
     *
     * @param SaveValidatorRequest $request Request with new validator information
     * @param Validator $validator Validator to update
     *
     * @return Response
     *
     * @throws RepositoryException
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(SaveValidatorRequest $request, Validator $validator): Response
    {
        $this->validatorService->assignBus($validator, $request->getValidatorBusData()->bus_id);

        return $this->response->item($validator, $this->transformer);
    }
}
