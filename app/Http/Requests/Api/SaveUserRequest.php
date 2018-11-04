<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\UserData;
use App\Domain\Enums\RolesIdentifiers;
use App\Models\User;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;
use Saritasa\Laravel\Validation\RuleSet;

/**
 * SaveUserRequest form request.
 *
 * @property-read integer $id User identifier
 * @property-read integer $role_id User role identifier
 * @property-read integer|null $company_id Company identifier in which user works
 * @property-read string $first_name User first name
 * @property-read string $last_name User last name
 * @property-read string $email User email address
 * @property-read string $password User password
 */
class SaveUserRequest extends ApiRequest
{
    /**
     * Rules that should be applied to validate request.
     *
     * @return string[]|GenericRuleSet[]
     */
    public function rules(): array
    {
        return [
            User::ROLE_ID => Rule::required()->exists('roles', 'id')->int(),
            User::COMPANY_ID => Rule::exists('companies', 'id')->int()->when(
                in_array($this->role_id, [RolesIdentifiers::OPERATOR]),
                function (RuleSet $rules) {
                    return $rules->required();
                },
                function (RuleSet $rules) {
                    return $rules->nullable();
                }
            ),
            User::FIRST_NAME => Rule::required()->string()->max(191),
            User::LAST_NAME => Rule::required()->string()->max(191),
            User::EMAIL => Rule::required()->string()->max(191),
            User::PASSWORD => Rule::string()->max(191)->when(!$this->id, function (RuleSet $rules) {
                return $rules->required();
            }),
        ];
    }

    /**
     * Returns user details.
     *
     * @return UserData
     */
    public function getUserData(): UserData
    {
        return new UserData($this->all());
    }
}
