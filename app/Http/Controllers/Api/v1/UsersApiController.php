<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\UserEntityService;
use App\Http\Requests\Api\SaveUserRequest;
use App\Models\User;
use Dingo\Api\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Saritasa\Transformers\IDataTransformer;

/**
 * Users requests API controller.
 */
class UsersApiController extends BaseApiController
{
    /**
     * Users entity service.
     *
     * @var UserEntityService
     */
    private $userService;

    /**
     * Users requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param UserEntityService $userService Users entity service
     */
    public function __construct(IDataTransformer $transformer, UserEntityService $userService)
    {
        parent::__construct($transformer);
        $this->userService = $userService;
    }

    /**
     * Returns users list.
     *
     * @return Response
     *
     * @throws InvalidEnumValueException In case of invalid order direction usage
     */
    public function index(): Response
    {
        return $this->response->collection(
            $this->userService->getWith(
                ['role', 'company'],
                [],
                $this->singleCompanyUser() ? [User::COMPANY_ID => $this->user->company_id] : [],
                new SortOptions(User::ID)
            ),
            $this->transformer
        );
    }

    /**
     * Stores new user in application.
     *
     * @param SaveUserRequest $request Request with new user information
     *
     * @return Response
     *
     * @throws RepositoryException
     * @throws ValidationException
     */
    public function store(SaveUserRequest $request): Response
    {
        $user = $this->userService->store($request->getUserData());

        return $this->response->item($user, $this->transformer);
    }

    /**
     * Updates user details.
     *
     * @param SaveUserRequest $request Request with new user information
     * @param User $user User to update
     *
     * @return Response
     *
     * @throws RepositoryException
     * @throws ValidationException
     */
    public function update(SaveUserRequest $request, User $user): Response
    {
        $this->userService->update($user, $request->getUserData());

        return $this->response->item($user, $this->transformer);
    }

    /**
     * Removes user from application.
     *
     * @param User $user User to delete
     *
     * @return Response
     *
     * @throws RepositoryException
     * @throws AuthorizationException
     */
    public function destroy(User $user): Response
    {
        if ($this->user->getKey() === $user->getKey()) {
            $this->deny();
        }
        $this->userService->destroy($user);

        return $this->response->noContent();
    }
}
