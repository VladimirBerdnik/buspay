<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Request;

/**
 * Base API request. Authorizes all requests by default.
 */
abstract class ApiRequest extends Request
{
    /**
     * Returns whether this request authorized for executing or not.
     *
     * @return boolean
     */
    public function authorize(): bool
    {
        return true;
    }
}
