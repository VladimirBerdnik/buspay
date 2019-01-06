<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\DriverEntityService;
use App\Domain\Enums\Abilities;
use App\Http\Requests\Api\SaveDriverRequest;
use App\Models\Driver;
use Dingo\Api\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Saritasa\Transformers\IDataTransformer;
use Throwable;

/**
 * Drivers requests API controller.
 */
class DriversApiController extends BaseApiController
{
    /**
     * Drivers entity service.
     *
     * @var DriverEntityService
     */
    private $driverService;

    /**
     * Drivers requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param DriverEntityService $driverService Drivers entity service
     */
    public function __construct(IDataTransformer $transformer, DriverEntityService $driverService)
    {
        parent::__construct($transformer);
        $this->driverService = $driverService;
    }

    /**
     * Returns drivers list.
     *
     * @return Response
     *
     * @throws InvalidEnumValueException In case of invalid order direction usage
     * @throws AuthorizationException
     */
    public function index(): Response
    {
        $this->authorize(Abilities::GET, new Driver());

        return $this->response->collection(
            $this->driverService->getWith(
                ['company', 'bus', 'card'],
                [],
                $this->singleCompanyUser() ? [Driver::COMPANY_ID => $this->user->company_id] : [],
                new SortOptions(Driver::FULL_NAME)
            ),
            $this->transformer
        );
    }

    /**
     * Stores new driver in application.
     *
     * @param SaveDriverRequest $request Request with new driver information
     *
     * @return Response
     *
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(SaveDriverRequest $request): Response
    {
        $this->authorize(Abilities::CREATE, new Driver());

        $driver = $this->driverService->store($request->getDriverFullData());

        return $this->response->item($driver, $this->transformer);
    }

    /**
     * Updates driver details.
     *
     * @param SaveDriverRequest $request Request with new driver information
     * @param Driver $driver Driver to update
     *
     * @return Response
     *
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(SaveDriverRequest $request, Driver $driver): Response
    {
        if (!$this->user->can(Abilities::UPDATE, $driver)) {
            if (!$this->user->can(Abilities::CHANGE_DRIVER_BUS, $driver)) {
                $this->deny();
            }
            $driverData = $request->getDriverBusData();
        } else {
            $driverData = $request->getDriverFullData();
        }

        $this->driverService->update($driver, $driverData);

        return $this->response->item($driver, $this->transformer);
    }

    /**
     * Removes driver from application.
     *
     * @param Driver $driver Driver to delete
     *
     * @return Response
     *
     * @throws RepositoryException
     * @throws Throwable
     */
    public function destroy(Driver $driver): Response
    {
        $this->authorize(Abilities::DELETE, $driver);

        $this->driverService->destroy($driver);

        return $this->response->noContent();
    }
}
