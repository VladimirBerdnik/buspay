<?php

namespace App\Domain\Exceptions\Constraint;

use App\Extensions\ActivityPeriod\IActivityPeriod;
use App\Models\CompaniesRoute;

/**
 * Thrown when company to route assignment already exists.
 */
class CompanyRouteExistsException extends BusinessLogicConstraintException
{
    /**
     * Company to route assignment for which activity period exists.
     *
     * @var CompaniesRoute
     */
    protected $companiesRoute;

    /**
     * Thrown when company to route assignment already exists.
     *
     * @param CompaniesRoute|IActivityPeriod $companiesRoute Company to route assignment for which activity period
     *     exists
     */
    public function __construct(CompaniesRoute $companiesRoute)
    {
        parent::__construct('Route activity period already exists exception');
        $this->companiesRoute = $companiesRoute;
    }
}
