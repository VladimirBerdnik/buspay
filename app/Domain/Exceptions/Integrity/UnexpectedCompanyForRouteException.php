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
    private $companiesRoute;

    /**
     * Expected company
     *
     * @var Company
     */
    private $company;

    /**
     * Thrown when company to route assignment has unexpected company value.
     *
     * @param CompaniesRoute $companiesRoute Company to route assignment where unexpected company found
     * @param Company $company Expected company
     */
    public function __construct(CompaniesRoute $companiesRoute, Company $company)
    {
        parent::__construct('Unexpected company in company route');
        $this->companiesRoute = $companiesRoute;
        $this->company = $company;
    }

    /**
     * Company to route assignment where unexpected company found.
     *
     * @return CompaniesRoute
     */
    public function getCompaniesRoute(): CompaniesRoute
    {
        return $this->companiesRoute;
    }

    /**
     * Expected company.
     *
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $companiesRoute = $this->getCompaniesRoute();

        return "Unexpected company [{$companiesRoute->company_id}] for route [{$companiesRoute->route_id}] found " .
            "in company to route assignment [{$companiesRoute->id}]. " .
            "Expected company [{$this->getCompany()->id}]";
    }
}
