<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\Services\CompanyService;
use App\Models\Company;
use Dingo\Api\Http\Response;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
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
            $this->companyService->getWith([], [], [], new SortOptions(Company::NAME)),
            $this->transformer
        );
    }
}
