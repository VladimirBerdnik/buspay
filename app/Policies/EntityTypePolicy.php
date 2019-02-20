<?php

namespace App\Policies;

use App\Domain\Enums\Abilities;
use App\Domain\Enums\EntitiesTypes;
use App\Domain\Enums\RolesIdentifiers;
use App\Domain\IBelongsToCompany;
use App\Models\Bus;
use App\Models\Card;
use App\Models\CardType;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Replenishment;
use App\Models\Role;
use App\Models\Route;
use App\Models\RouteSheet;
use App\Models\Tariff;
use App\Models\TariffPeriod;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Validator;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Log;
use Saritasa\Exceptions\NotImplementedException;

/**
 * General policy to check ability for users. Uses policies configuration to check access.
 */
class EntityTypePolicy
{
    use HandlesAuthorization;

    /**
     * First level of policy check. Allows to admin do any ability.
     *
     * @param User $user User for which need to check ability
     * @param string $ability Ability to check
     *
     * @return boolean|null
     *
     * @throws NotImplementedException
     */
    public function before(User $user, string $ability): ?bool
    {
        if (!Abilities::isValidValue($ability)) {
            return false;
        }

        if ($user->hasRole(RolesIdentifiers::ADMIN)) {
            return true;
        }

        return null;
    }

    /**
     * Checks whether given ability is allowed for given user to perform with given entity class.
     *
     * @param User $user User to check ability for
     * @param string $ability Ability to check
     * @param string $entityClass Entity class to check ability on
     *
     * @return boolean
     */
    protected function checkAbilityForEntityClass(User $user, string $ability, string $entityClass): bool
    {
        $entityClassToTypeMap = [
            Bus::class => EntitiesTypes::BUS,
            CardType::class => EntitiesTypes::CARD_TYPE,
            Card::class => EntitiesTypes::CARD,
            Company::class => EntitiesTypes::COMPANY,
            Driver::class => EntitiesTypes::DRIVER,
            Role::class => EntitiesTypes::ROLE,
            RouteSheet::class => EntitiesTypes::ROUTE_SHEET,
            Route::class => EntitiesTypes::ROUTE,
            Tariff::class => EntitiesTypes::TARIFF,
            TariffPeriod::class => EntitiesTypes::TARIFF_PERIOD,
            User::class => EntitiesTypes::USER,
            Validator::class => EntitiesTypes::VALIDATOR,
            Replenishment::class => EntitiesTypes::REPLENISHMENT,
            Transaction::class => EntitiesTypes::TRANSACTION,
        ];

        $entityType = $entityClassToTypeMap[$entityClass];

        $allowedEntityAbilities = config("policies.{$entityType}") ?? [];

        if (!isset($allowedEntityAbilities[$ability])) {
            Log::error("Ability [{$entityType}.{$ability}] not allowed but requested by user [{$user->id}]");
        }

        $allowedAbilityRoles = $allowedEntityAbilities[$ability] ?? [];

        return in_array($user->role_id, $allowedAbilityRoles);
    }

    /**
     * Checks whether given ability is allowed for given user to perform with given entity.
     *
     * @param User $user User to check ability for
     * @param string $ability Ability to check
     * @param Model $entity Entity to check ability on
     *
     * @return boolean
     */
    protected function checkAbilityForEntity(User $user, string $ability, Model $entity): bool
    {
        // If user works in company than he can deal only with entities with same company
        if ($user->company_id && $entity instanceof IBelongsToCompany) {
            if ($user->company_id !== $entity->company_id) {
                return false;
            }
        }

        return $this->checkAbilityForEntityClass($user, $ability, get_class($entity));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user User for which need to check ability
     * @param Model $model Model for which need to check policy
     *
     * @return boolean
     */
    public function show(User $user, Model $model): bool
    {
        return $this->checkAbilityForEntity($user, Abilities::SHOW, $model);
    }

    /**
     * Determine whether the user can retrieve list of models.
     *
     * @param User $user User for which need to check ability
     * @param Model $model Example of model to check ability for. Can be not existing model class
     *
     * @return boolean
     */
    public function get(User $user, Model $model): bool
    {
        return $this->checkAbilityForEntityClass($user, Abilities::GET, get_class($model));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user User for which need to check ability
     * @param Model $model Example of model to check ability for. Can be not existing model class
     *
     * @return boolean
     */
    public function create(User $user, Model $model): bool
    {
        return $this->checkAbilityForEntity($user, Abilities::UPDATE, $model);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user User for which need to check ability
     * @param Model $model Model for which need to check policy
     *
     * @return boolean
     */
    public function update(User $user, Model $model): bool
    {
        return $this->checkAbilityForEntity($user, Abilities::UPDATE, $model);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user User for which need to check ability
     * @param Model $model Model for which need to check policy
     *
     * @return boolean
     */
    public function delete(User $user, Model $model): bool
    {
        return $this->checkAbilityForEntity($user, Abilities::DELETE, $model);
    }
}
