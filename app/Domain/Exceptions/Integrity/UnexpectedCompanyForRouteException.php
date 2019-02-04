<?php

namespace App\Domain\Exceptions\Integrity;

use App\Models\CompaniesRoute;
use App\Models\Company;

/**
 * Thrown when company to route assignment has unexpected company value.
 */
class UnexpectedCompanyForRouteException extends BusinessLogicIntegrityException
{
    /**
     * Company to route assignment where unexpected company found.
     *
     * @var CompaniesRoute
     */
    protected $companiesRoute;

    /**
     * Expected company.
     *
     * @var Company
     */
    protected $company;

    /**
     * Thrown when company to route assignment has unexpected company value.
     *
     * @param CompaniesRoute $companiesRoute Company to route assignment where unexpected company found
     * @param Company $company Expected company
     */
    public function __construct(CompaniesRoute $companiesRoute, Company $company)
    {
        parent::__construct('Unexpected company in company to route historical assignment');
        $this->companiesRoute = $companiesRoute;
        $this->company = $company;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $companiesRoute = $this->companiesRoute;

        return "Unexpected company [{$companiesRoute->company_id}] for route [{$companiesRoute->route_id}] found " .
            "in company to route assignment [{$companiesRoute->id}]. Expected company is [{$this->company->id}]";
    }
}
