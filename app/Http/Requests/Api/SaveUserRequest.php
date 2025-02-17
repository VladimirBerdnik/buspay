<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\UserData;
use App\Models\User;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * Request to save user details.
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
            User::ROLE_ID => Rule::required()->int(),
            User::FIRST_NAME => Rule::required()->string()->max(64),
            User::LAST_NAME => Rule::required()->string()->max(64),
            User::EMAIL => Rule::required()->email()->max(64),
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
