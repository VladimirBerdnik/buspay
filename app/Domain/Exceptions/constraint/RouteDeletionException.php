<?php

namespace App\Domain\Exceptions\Constraint;

use App\Models\Route;

/**
 * Thrown when route cannot be deleted due to related records restrictions.
 */
class RouteDeletionException extends BusinessLogicConstraintException
{
    /**
     * Route that can't be deleted.
     *
     * @var Route
     */
    private $route;

    /**
     * Thrown when route cannot be deleted due to related records restrictions.
     *
     * @param Route $route Route that can't be deleted
     */
    public function __construct(Route $route)
    {
        parent::__construct('Route with related records cannot be deleted');
        $this->route = $route;
    }

    /**
     * Returns route that can't be deleted.
     *
     * @return Route
     */
    public function getRoute(): Route
    {
        return $this->route;
    }
}
