<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\RoleEntityService;
use App\Models\Role;
use Dingo\Api\Http\Response;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\Transformers\IDataTransformer;

/**
 * Roles requests API controller.
 */
class RolesApiController extends BaseApiController
{
    /**
     * Roles business-logic service.`
     *
     * @var RoleEntityService
     */
    private $roleService;

    /**
     * Roles requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param RoleEntityService $roleService Roles business logic service
     */
    public function __construct(IDataTransformer $transformer, RoleEntityService $roleService)
    {
        parent::__construct($transformer);
        $this->roleService = $roleService;
    }

    /**
     * Returns roles list.
     *
     * @return Response
     *
     * @throws InvalidEnumValueException In case of invalid order direction usage
     */
    public function index(): Response
    {
        return $this->response->collection(
            $this->roleService->getWith([], [], [], new SortOptions(Role::ID)),
            $this->transformer
        );
    }
}
