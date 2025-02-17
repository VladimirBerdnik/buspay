<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\BusEntityService;
use App\Domain\Enums\Abilities;
use App\Http\Requests\Api\SaveBusRequest;
use App\Models\Bus;
use Dingo\Api\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
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
     * Buses entity service.
     *
     * @var BusEntityService
     */
    private $busService;

    /**
     * Buses requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param BusEntityService $busService Buses entity service
     */
    public function __construct(IDataTransformer $transformer, BusEntityService $busService)
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
     * @throws AuthorizationException
     */
    public function index(): Response
    {
        $this->authorize(Abilities::GET, new Bus());

        return $this->response->collection(
            $this->busService->getWith(
                ['company', 'route'],
                ['drivers', 'validators'],
                $this->singleCompanyUser() ? [Bus::COMPANY_ID => $this->user->company_id] : [],
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
        $this->authorize(Abilities::CREATE, new Bus());

        $bus = $this->busService->store($request->getBusFullData());

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
        if (!$this->user->can(Abilities::UPDATE, $bus)) {
            if (!$this->user->can(Abilities::CHANGE_BUS_ROUTE, $bus)) {
                $this->deny();
            }
            $busData = $request->getBusRouteData();
        } else {
            $busData = $request->getBusFullData();
        }

        $this->busService->update($bus, $busData);

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
        $this->authorize(Abilities::DELETE, $bus);

        $this->busService->destroy($bus);

        return $this->response->noContent();
    }
}
