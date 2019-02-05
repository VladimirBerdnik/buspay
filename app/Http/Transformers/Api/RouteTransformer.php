<?php

namespace App\Http\Transformers\Api;

use App\Models\Route;
use Illuminate\Contracts\Support\Arrayable;
use League\Fractal\Resource\ResourceInterface;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms route to display on routes page.
 */
class RouteTransformer extends BaseTransformer
{
    public const INCLUDE_COMPANY = 'company';

    /**
     * Include resources without needing it to be requested.
     *
     * @var string[]
     */
    protected $defaultIncludes = [
        self::INCLUDE_COMPANY,
    ];

    /**
     * Company transformer to display as route relation.
     *
     * @var CompanyTransformer
     */
    private $companyTransformer;

    /**
     * Transforms route to display on routes page.
     *
     * @param CompanyTransformer $companyTransformer Company transformer to display as route relation
     */
    public function __construct(CompanyTransformer $companyTransformer)
    {
        $this->companyTransformer = $companyTransformer;

        $this->companyTransformer->setDefaultIncludes([]);
    }

    /**
     * Transforms route to display on route page.
     *
     * @param Arrayable $model Model to transform
     *
     * @return string[]
     *
     * @throws TransformTypeMismatchException
     */
    public function transform(Arrayable $model): array
    {
        if (!$model instanceof Route) {
            throw new TransformTypeMismatchException($this, Route::class, get_class($model));
        }

        return $this->transformModel($model);
    }

    /**
     * Transforms route into appropriate format.
     *
     * @param Route $route Route to transform
     *
     * @return string[]
     */
    protected function transformModel(Route $route): array
    {
        return [
            Route::ID => $route->id,
            Route::NAME => $route->name,
            Route::COMPANY_ID => $route->company_id,
            // possible related records count
            'buses_count' => $route->getAttribute('buses_count'),
        ];
    }

    /**
     * Company transformer to display as route relation.
     *
     * @return CompanyTransformer
     */
    public function getCompanyTransformer(): CompanyTransformer
    {
        return $this->companyTransformer;
    }

    /**
     * Includes company into transformed response.
     *
     * @param Route $route Route to retrieve company details
     *
     * @return ResourceInterface
     */
    protected function includeCompany(Route $route): ResourceInterface
    {
        if (!$route->company) {
            return $this->null();
        }

        return $this->item($route->company, $this->companyTransformer);
    }
}
