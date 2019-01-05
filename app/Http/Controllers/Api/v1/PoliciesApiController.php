<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\JsonResponse;

/**
 * Policies requests API controller.
 */
class PoliciesApiController extends BaseApiController
{
    /**
     * Returns allowed for different roles policies configuration.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return new JsonResponse(config('policies'));
    }
}
