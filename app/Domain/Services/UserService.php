<?php

namespace App\Domain\Services;

use App\Domain\Dto\UserData;
use App\Extensions\EntityService;
use App\Models\User;
use Log;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;

/**
 * User business-logic service.
 */
class UserService extends EntityService
{
    /**
     * Stores new user.
     *
     * @param UserData $userData User details to create
     *
     * @return User
     *
     * @throws RepositoryException
     */
    public function store(UserData $userData): User
    {
        Log::debug("Create user with email [{$userData->email}] attempt");

        $user = new User($userData->toArray());

        $this->getRepository()->create($user);

        Log::debug("User [{$user->id}] created");

        return $user;
    }

    /**
     * Updates user details.
     *
     * @param User $user User to update
     * @param UserData $userData User new details
     *
     * @return User
     *
     * @throws RepositoryException
     */
    public function update(User $user, UserData $userData): User
    {
        Log::debug("Update user [{$user->id}] attempt");

        $newAttributes = $userData->toArray();

        if (!$userData->password) {
            unset($newAttributes[UserData::PASSWORD]);
        }

        $user->fill($newAttributes);

        $this->getRepository()->save($user);

        Log::debug("User [{$user->id}] updated");

        return $user;
    }

    /**
     * Deletes user.
     *
     * @param User $user User to delete
     *
     * @throws RepositoryException
     */
    public function destroy(User $user): void
    {
        Log::debug("Delete user [{$user->id}] attempt");

        $this->getRepository()->delete($user);

        Log::debug("User [{$user->id}] deleted");
    }
}
