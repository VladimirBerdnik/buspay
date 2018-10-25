<?php

namespace App\Exceptions;

use Dingo\Api\Facade\API;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Saritasa\DingoApi\Exceptions\ApiExceptionHandler as DingoApiExceptionHandler;
use Saritasa\Exceptions\InvalidEnumValueException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Handles app exceptions and converts into dingo api response format.
 */
class ApiExceptionHandler extends DingoApiExceptionHandler
{
    /**
     * Handles app exceptions and converts into dingo api response format.
     *
     * @param ExceptionHandler $parentHandler Parent exception handler
     */
    public function __construct(ExceptionHandler $parentHandler)
    {
        parent::__construct($parentHandler);
        $this->registerCustomHandlers();
    }

    /**
     * Registers error handlers for exceptions.
     * We can't pass class method as callback directly, so we wrap them into closures.
     *
     * @return void
     */
    private function registerCustomHandlers(): void
    {
        API::error(function (InvalidEnumValueException $exception) {
            return $this->handle(new BadRequestHttpException($exception->getMessage(), $exception));
        });
    }
}
