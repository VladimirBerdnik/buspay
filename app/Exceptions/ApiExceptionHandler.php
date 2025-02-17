<?php

namespace App\Exceptions;

use App\Domain\Exceptions\Constraint\BusinessLogicConstraintException;
use App\Domain\Exceptions\Integrity\BusinessLogicIntegrityException;
use Dingo\Api\Exception\RateLimitExceededException;
use Dingo\Api\Facade\API;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\DingoApi\Exceptions\ApiExceptionHandler as DingoApiExceptionHandler;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelControllers\Responses\ErrorMessage;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Handles app exceptions and converts into dingo api response format.
 */
class ApiExceptionHandler extends DingoApiExceptionHandler
{
    /**
     * List of exception classes that should be handled with simple message replacement or translate.
     *
     * @var string[]
     */
    protected $generalApplicationExceptions = [
        RateLimitExceededException::class,
    ];

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
        API::error(function (ValidationException $exception) {
            return $this->handleValidation(
                new TranslatableValidationException($exception->validator, $exception->response, $exception->errorBag)
            );
        });
        API::error(function (BusinessLogicConstraintException $exception) {
            return $this->genericResponse($exception);
        });
        API::error(function (BusinessLogicIntegrityException $exception) {
            return $this->genericResponse($exception);
        });
        API::error(function (RateLimitExceededException $exception) {
            return $this->genericResponse($exception);
        });
    }

    /**
     * Retrieves user-friendly error message from exception.
     *
     * @param Exception $exception Exception to retrieve message from
     *
     * @return string
     */
    private function getExceptionMessage(Exception $exception): string
    {
        $exceptionClass = get_class($exception);

        if ($exception instanceof BusinessLogicConstraintException) {
            $translationKey = "exceptions.constraint.{$exceptionClass}";
        } elseif ($exception instanceof BusinessLogicIntegrityException) {
            $translationKey = "exceptions.integrity.{$exceptionClass}";
        } else {
            $translationKey = "exceptions.general.{$exceptionClass}";
        }

        $message = trans($translationKey);
        if (!$message || $message === $translationKey) {
            Log::notice("No translate for exception class [{$exceptionClass}] found");
            $message = $exception->getMessage() ?? $exception->__toString() ?? $exceptionClass;
        }

        return $message;
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Exception $exception Exception to render
     *
     * @return Response
     *
     * @throws Exception
     */
    protected function genericResponse(Exception $exception): Response
    {
        if ($exception instanceof BusinessLogicConstraintException) {
            // All user attempts to break entity constraints that was successfully caught by application
            $message = $this->getExceptionMessage($exception);

            return response()->make(new ErrorMessage($message), Response::HTTP_BAD_REQUEST);
        } elseif ($exception instanceof BusinessLogicIntegrityException) {
            // All breaks of application entity exceptions that was detected
            $message = $this->getExceptionMessage($exception);

            return response()->make(new ErrorMessage($message), Response::HTTP_INTERNAL_SERVER_ERROR);
        } elseif (in_array(get_class($exception), $this->generalApplicationExceptions)) {
            // All breaks of application entity exceptions that was detected
            $message = $this->getExceptionMessage($exception);

            return response()->make(new ErrorMessage($message), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return parent::genericResponse($exception);
    }
}
