<?php

namespace App\Http\Controllers\Api\v1;

use Dingo\Api\Http\Response;
use Saritasa\LaravelControllers\Api\BaseApiController;

/**
 * Authorized user requests API controller.
 */
class ProfileApiController extends BaseApiController
{
    /**
     * Returns authorized user details.
     *
     * @return Response
     */
    public function me(): Response
    {
        return $this->json($this->user);
    }
}
