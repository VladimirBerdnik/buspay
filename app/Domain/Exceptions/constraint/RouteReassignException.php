<?php

namespace App\Domain\Exceptions\Constraint;

use App\Models\Route;

/**
 * Thrown when route cannot be reassigned to another company due to related records restrictions.
 */
class RouteReassignException extends BusinessLogicConstraintException
{
    /**
     * Route that can't be reassigned.
     *
     * @var Route
     */
    private $route;

    /**
     * Thrown when route cannot be reassigned to another company due to related records restrictions.
     *
     * @param Route $route Route that can't be reassigned
     */
    public function __construct(Route $route)
    {
        parent::__construct('Route with related to company records cannot be reassigned');
        $this->route = $route;
    }

    /**
     * Returns route that can't be reassigned.
     *
     * @return Route
     */
    public function getRoute(): Route
    {
        return $this->route;
    }
}
