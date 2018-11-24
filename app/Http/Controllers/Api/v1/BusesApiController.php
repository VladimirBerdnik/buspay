<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\BusService;
use App\Http\Requests\Api\SaveBusRequest;
use App\Models\Bus;
use Dingo\Api\Http\Response;
use Illuminate\Validation\ValidationException;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Saritasa\Transformers\IDataTransformer;
use Throwable;

/**
 * Buses requests API controller.
 */
class BusesApiController extends BaseApiController
{
    /**
     * Buses business-logic service.
     *
     * @var BusService
     */
    private $busService;

    /**
     * Buses requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param BusService $busService Buses business logic service
     */
    public function __construct(IDataTransformer $transformer, BusService $busService)
    {
        parent::__construct($transformer);
        $this->busService = $busService;
    }

    /**
     * Returns buses list.
     *
     * @return Response
     *
     * @throws InvalidEnumValueException
     */
    public function index(): Response
    {
        return $this->response->collection(
            $this->busService->getWith(
                ['company', 'route'],
                ['drivers', 'validators'],
                [],
                new SortOptions(Bus::COMPANY_ID)
            ),
            $this->transformer
        );
    }

    /**
     * Stores new bus in application.
     *
     * @param SaveBusRequest $request Request with new bus information
     *
     * @return Response
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(SaveBusRequest $request): Response
    {
        $bus = $this->busService->store($request->getBusData());

        return $this->response->item($bus, $this->transformer);
    }

    /**
     * Updates bus details.
     *
     * @param SaveBusRequest $request Request with new bus information
     * @param Bus $bus Bus to update
     *
     * @return Response
     *
     * @throws RepositoryException
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(SaveBusRequest $request, Bus $bus): Response
    {
        $this->busService->update($bus, $request->getBusData());

        return $this->response->item($bus, $this->transformer);
    }

    /**
     * Removes bus from application.
     *
     * @param Bus $bus Bus to delete
     *
     * @return Response
     *
     * @throws RepositoryException
     * @throws Throwable
     */
    public function destroy(Bus $bus): Response
    {
        $this->busService->destroy($bus);

        return $this->response->noContent();
    }
}
