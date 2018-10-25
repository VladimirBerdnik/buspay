<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Base application request.
 */
abstract class Request extends FormRequest
{
    /**
     * Authorizes usage of this request.
     *
     * @return boolean
     */
    public function authorize(): bool
    {
        return true;
    }
}
