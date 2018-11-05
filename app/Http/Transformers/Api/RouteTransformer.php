<?php

namespace App\Http\Transformers\Api;

use App\Models\Route;
use Illuminate\Contracts\Support\Arrayable;
use League\Fractal\Resource\ResourceInterface;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms route to display on route page.
 */
class RouteTransformer extends BaseTransformer
{
    /**
     * Include resources without needing it to be requested.
     *
     * @var string[]
     */
    protected $defaultIncludes = [
        'company',
    ];

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
            'id' => $route->id,
            'name' => $route->name,
            'company_id' => $route->company_id,
        ];
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

        return $this->item($route->company, app(CompanyTransformer::class));
    }
}
