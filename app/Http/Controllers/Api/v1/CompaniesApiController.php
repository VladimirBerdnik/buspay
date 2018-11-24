<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\CompanyService;
use App\Http\Requests\Api\SaveCompanyRequest;
use App\Models\Company;
use Dingo\Api\Http\Response;
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
     * Companies business-logic service.
     *
     * @var CompanyService
     */
    private $companyService;

    /**
     * Companies requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param CompanyService $companyService Companies business logic service
     */
    public function __construct(IDataTransformer $transformer, CompanyService $companyService)
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
     */
    public function index(): Response
    {
        return $this->response->collection(
            $this->companyService->getWith(
                [],
                ['buses', 'drivers', 'routes', 'users'],
                [],
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
     */
    public function store(SaveCompanyRequest $request): Response
    {
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
     */
    public function update(SaveCompanyRequest $request, Company $company): Response
    {
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
     */
    public function destroy(Company $company): Response
    {
        $this->companyService->destroy($company);

        return $this->response->noContent();
    }
}
