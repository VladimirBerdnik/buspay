<?php

namespace App\Exceptions;

use App\Domain\Exceptions\Constraint\BusinessLogicConstraintException;
use App\Extensions\ErrorsReporter;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Application exceptions handler.
 */
class Handler extends ExceptionHandler
{
    /**
     * Application errors reporter.
     *
     * @var ErrorsReporter
     */
    protected $errorsReporter;

    /**
     * Application exceptions handler.
     *
     * @param Container $container Services container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

        $this->errorsReporter = $this->container->make(ErrorsReporter::class);
    }

    /**
     * A list of the exception types that should not be reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
        BusinessLogicConstraintException::class,
        CommandNotFoundException::class,
    ];

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param Request $request Current HTTP request
     * @param AuthenticationException $exception Exception risen during request processing
     *
     * @return Response
     */
    protected function unauthenticated($request, AuthenticationException $exception): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED);
        }

        return redirect()->guest('login');
    }

    /**
     * Reports about thrown exception.
     *
     * @param Exception $exception Thrown exception that should be reported
     *
     * @return mixed|void
     *
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        if ($this->shouldntReport($exception)) {
            return;
        }

        $this->errorsReporter->reportException($exception, $this->context());

        parent::report($exception);
    }
}
