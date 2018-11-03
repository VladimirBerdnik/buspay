<?php

namespace App\Domain\Services;

use App\Domain\Dto\CompanyData;
use App\Extensions\EntityService;
use App\Models\Company;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;

/**
 * Company business-logic service.
 */
class CompanyService extends EntityService
{
    /**
     * Stores new company.
     *
     * @param CompanyData $companyData Company details to create
     *
     * @return Company
     *
     * @throws RepositoryException
     */
    public function store(CompanyData $companyData): Company
    {
        $company = new Company($companyData->toArray());

        $this->getRepository()->save($company);

        return $company;
    }

    /**
     * Updates company details.
     *
     * @param Company $company Company to update
     * @param CompanyData $companyData Company new details
     *
     * @return Company
     *
     * @throws RepositoryException
     */
    public function update(Company $company, CompanyData $companyData): Company
    {
        $company->fill($companyData->toArray());

        $this->getRepository()->save($company);

        return $company;
    }
}
