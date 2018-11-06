<?php

namespace App\Exceptions;

use App\Domain\Exceptions\Constraint\BusinessLogicConstraintException;
use App\Domain\Exceptions\Constraint\CompanyDeletionException;
use App\Domain\Exceptions\Constraint\CompanyRouteExistsException;
use App\Domain\Exceptions\Constraint\RouteDeletionException;
use App\Domain\Exceptions\Constraint\RouteReassignException;
use App\Domain\Exceptions\Integrity\BusinessLogicIntegrityException;
use Dingo\Api\Facade\API;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
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
        if ($exception instanceof RouteDeletionException) {
            return response()
                ->json(new ErrorMessage(trans('exceptions.routeDeletionException')), Response::HTTP_BAD_REQUEST);
        } elseif ($exception instanceof RouteReassignException) {
            return response()
                ->json(new ErrorMessage(trans('exceptions.routeReassignmentException')), Response::HTTP_BAD_REQUEST);
        } elseif ($exception instanceof CompanyDeletionException) {
            return response()
                ->json(new ErrorMessage(trans('exceptions.companyDeletionException')), Response::HTTP_BAD_REQUEST);
        } elseif ($exception instanceof CompanyRouteExistsException) {
            return response()
                ->json(new ErrorMessage(trans('exceptions.companyRouteExistsException')), Response::HTTP_BAD_REQUEST);
        } elseif ($exception instanceof BusinessLogicIntegrityException) {
            return response()
                ->make(new ErrorMessage($exception->__toString()), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return parent::genericResponse($exception);
    }
}
