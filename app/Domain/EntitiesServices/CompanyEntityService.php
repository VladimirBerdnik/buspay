<?php

namespace App\Domain\EntitiesServices;

use App\Domain\Dto\CompanyData;
use App\Domain\Exceptions\Constraint\CompanyDeletionException;
use App\Extensions\EntityService;
use App\Models\Company;
use Log;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;

/**
 * Company entity service.
 */
class CompanyEntityService extends EntityService
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
        Log::debug("Create company with BIN [{$companyData->bin}] attempt");

        $company = new Company($companyData->toArray());

        $this->getRepository()->create($company);

        Log::debug("Company [{$company->id}] created");

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
        Log::debug("Update company [{$company->id}] attempt");

        $company->fill($companyData->toArray());

        $this->getRepository()->save($company);

        Log::debug("Company [{$company->id}] updated");

        return $company;
    }

    /**
     * Deletes company.
     *
     * @param Company $company Company to delete
     *
     * @throws RepositoryException
     */
    public function destroy(Company $company): void
    {
        Log::debug("Delete company [{$company->id}] attempt");

        if ($company->routes->isNotEmpty()
            || $company->drivers->isNotEmpty()
            || $company->buses->isNotEmpty()
            || $company->users->isNotEmpty()
            || $company->routeSheets->isNotEmpty()
        ) {
            throw new CompanyDeletionException($company);
        }

        $this->getRepository()->delete($company);

        Log::debug("Company [{$company->id}] deleted");
    }
}
