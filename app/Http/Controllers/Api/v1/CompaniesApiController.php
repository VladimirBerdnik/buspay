<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\CompanyEntityService;
use App\Domain\Enums\Abilities;
use App\Http\Requests\Api\SaveCompanyRequest;
use App\Models\Company;
use Dingo\Api\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Saritasa\Transformers\IDataTransformer;

/**
 * Companies requests API controller.
 */
class CompaniesApiController extends BaseApiController
{
    /**
     * Companies entity service.
     *
     * @var CompanyEntityService
     */
    private $companyService;

    /**
     * Companies requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param CompanyEntityService $companyService Companies entity service
     */
    public function __construct(IDataTransformer $transformer, CompanyEntityService $companyService)
    {
        parent::__construct($transformer);
        $this->companyService = $companyService;
    }

    /**
     * Returns companies list.
     *
     * @return Response
     *
     * @throws InvalidEnumValueException In case of invalid order direction usage
     * @throws AuthorizationException
     */
    public function index(): Response
    {
        $this->authorize(Abilities::GET, new Company());

        return $this->response->collection(
            $this->companyService->getWith(
                [],
                ['buses', 'drivers', 'routes', 'users'],
                $this->singleCompanyUser() ? [Company::ID => $this->user->company_id] : [],
                new SortOptions(Company::NAME)
            ),
            $this->transformer
        );
    }

    /**
     * Stores new company in application.
     *
     * @param SaveCompanyRequest $request Request with new company information
     *
     * @return Response
     *
     * @throws RepositoryException
     * @throws AuthorizationException
     */
    public function store(SaveCompanyRequest $request): Response
    {
        $this->authorize(Abilities::CREATE, new Company());

        $company = $this->companyService->store($request->getCompanyData());

        return $this->response->item($company, $this->transformer);
    }

    /**
     * Updates company details.
     *
     * @param SaveCompanyRequest $request Request with new company information
     * @param Company $company Company to update
     *
     * @return Response
     *
     * @throws RepositoryException
     * @throws AuthorizationException
     */
    public function update(SaveCompanyRequest $request, Company $company): Response
    {
        $this->authorize(Abilities::UPDATE, $company);

        $this->companyService->update($company, $request->getCompanyData());

        return $this->response->item($company, $this->transformer);
    }

    /**
     * Removes company from application.
     *
     * @param Company $company Company to delete
     *
     * @return Response
     *
     * @throws RepositoryException
     * @throws AuthorizationException
     */
    public function destroy(Company $company): Response
    {
        $this->authorize(Abilities::DELETE, $company);

        $this->companyService->destroy($company);

        return $this->response->noContent();
    }
}
