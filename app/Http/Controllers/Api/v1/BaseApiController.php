<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Saritasa\LaravelControllers\Api\BaseApiController as SaritasaBaseApiController;

/**
 * Api controller with improved method to return collection of items.
 *
 * @property User|null $user Authenticated user
 */
class BaseApiController extends SaritasaBaseApiController
{
    use HandlesAuthorization;

    /**
     * Returns whether currently authenticated user is belongs to single company or not.
     *
     * @return boolean
     */
    protected function singleCompanyUser(): bool
    {
        return $this->user && $this->user->company_id;
    }
}
