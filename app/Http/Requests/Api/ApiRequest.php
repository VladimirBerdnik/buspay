<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Base API request. Authorizes all requests by default.
 */
abstract class ApiRequest extends FormRequest
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
