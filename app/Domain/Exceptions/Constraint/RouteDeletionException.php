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
    protected $route;

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
}
