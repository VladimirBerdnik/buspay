<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Auth\Access\HandlesAuthorization;
use Saritasa\LaravelControllers\Api\BaseApiController as SaritasaBaseApiController;

/**
 * Api controller with improved method to return collection of items.
 */
class BaseApiController extends SaritasaBaseApiController
{
    use HandlesAuthorization;
}
