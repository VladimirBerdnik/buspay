<?php

namespace App\Domain\Exceptions\Constraint;

use App\Models\RouteSheet;

/**
 * Thrown when route sheet cannot be deleted due to related records restrictions.
 */
class RouteSheetDeletionException extends BusinessLogicConstraintException
{
    /**
     * Route sheet that can't be deleted.
     *
     * @var RouteSheet
     */
    protected $route;

    /**
     * Thrown when route sheet cannot be deleted due to related records restrictions.
     *
     * @param RouteSheet $routeSheet Route that can't be deleted
     */
    public function __construct(RouteSheet $routeSheet)
    {
        parent::__construct('Route sheet with related records cannot be deleted');
        $this->route = $routeSheet;
    }
}
