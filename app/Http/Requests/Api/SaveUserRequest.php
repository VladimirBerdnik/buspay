<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\UserData;
use App\Models\User;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * SaveUserRequest form request.
 *
 * @property-read integer $role_id User role identifier
 * @property-read integer|null $company_id Company identifier in which user works
 * @property-read string $first_name User first name
 * @property-read string $last_name User last name
 * @property-read string $email User email address
 * @property-read string $password User password
 * @property-read string|null $remember_token Authorization token
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
            User::COMPANY_ID => Rule::nullable()->exists('companies', 'id')->int(),
            User::FIRST_NAME => Rule::required()->string()->max(191),
            User::LAST_NAME => Rule::required()->string()->max(191),
            User::EMAIL => Rule::required()->string()->max(191),
            User::PASSWORD => Rule::required()->string()->max(191),
            User::REMEMBER_TOKEN => Rule::nullable()->string()->max(191),
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
