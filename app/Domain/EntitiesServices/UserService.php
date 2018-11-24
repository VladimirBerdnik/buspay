<?php

namespace App\Domain\EntitiesServices;

use App\Domain\Dto\UserData;
use App\Domain\Enums\RolesIdentifiers;
use App\Extensions\EntityService;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\Laravel\Validation\DatabaseRuleSet;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;
use Saritasa\Laravel\Validation\RuleSet;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Validator;

/**
 * User business-logic service.
 */
class UserService extends EntityService
{
    /**
     * List of user roles that should have assigned company.
     *
     * @var string[]
     */
    protected $rolesWithCompany = [RolesIdentifiers::OPERATOR];

    /**
     * Returns validation rule to store or update user.
     *
     * @param UserData $userData User data to build conditional rules
     * @param User $user User to build rules for
     *
     * @return string[]|GenericRuleSet[]
     */
    protected function getUserValidationRules(UserData $userData, User $user): array
    {
        return [
            User::ROLE_ID => Rule::required()->exists('roles', Role::ID)->int(),
            User::FIRST_NAME => Rule::required()->string()->max(64),
            User::LAST_NAME => Rule::required()->string()->max(64),
            User::COMPANY_ID => Rule::when(
                in_array($userData->role_id, $this->rolesWithCompany),
                function (
                    RuleSet $rules
                ) {
                    // When user has role with company than company is required, otherwise should be empty
                    /**
                     * Database rules set.
                     *
                     * @var DatabaseRuleSet $rules
                     */
                    return $rules->exists('companies', Company::ID)->required();
                },
                function (RuleSet $rules) {
                    return $rules->nullable()->max(0);
                }
            ),
            User::EMAIL => Rule::required()
                // Email field should be unique
                ->unique('users', User::EMAIL, function (Unique $rule) use ($user) {
                    if ($user->exists) {
                        $rule->whereNot(User::ID, $user->id);
                    }

                    return $rule->whereNull(User::DELETED_AT);
                })
                ->string()->max(64),
            User::PASSWORD => Rule::when(!$user->exists || $userData->password, function (RuleSet $rules) {
                // For new user or for existing user with passed password
                return $rules->required()->max(64)->min(6);
            }),
        ];
    }

    /**
     * Stores new user.
     *
     * @param UserData $userData User details to create
     *
     * @return User
     *
     * @throws RepositoryException
     * @throws ValidationException
     */
    public function store(UserData $userData): User
    {
        Log::debug("Create user with email [{$userData->email}] attempt");

        $user = new User($userData->toArray());

        Validator::validate($userData->toArray(), $this->getUserValidationRules($userData, $user));

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
     * @throws ValidationException
     */
    public function update(User $user, UserData $userData): User
    {
        Log::debug("Update user [{$user->id}] attempt");

        Validator::validate($userData->toArray(), $this->getUserValidationRules($userData, $user));

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
