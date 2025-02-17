<?php

namespace App\Domain\Exceptions\Integrity;

use App\Models\Route;

/**
 * Thrown when company to route assignment not found, but expected.
 */
class NoCompanyForRouteException extends BusinessLogicIntegrityException
{
    /**
     * Route for which company not found.
     *
     * @var Route
     */
    protected $route;

    /**
     * Thrown when company to route assignment not found, but expected.
     *
     * @param Route $route Route for which company not found
     */
    public function __construct(Route $route)
    {
        parent::__construct('No company to route historical assignment found when expected');
        $this->route = $route;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $route = $this->route;

        return "No route [{$route->id}] to company [{$route->company_id}] historical assignment found but expected";
    }
}
