<?php

namespace App\Exceptions;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Patched validation exception with ability to translate main exception message.
 */
class TranslatableValidationException extends ValidationException
{
    /**
     * Create a new exception instance.
     *
     * @param Validator $validator Validator that was failed
     * @param Response|null $response Response that should be send to client
     * @param string $errorBag Error bag type
     */
    public function __construct(Validator $validator, ?Response $response = null, string $errorBag = 'default')
    {
        parent::__construct($validator, $response, $errorBag);
        $this->message = trans('errors.validation_failed');
    }
}
