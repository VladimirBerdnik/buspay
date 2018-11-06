<?php

namespace App\Http\Transformers\Api;

use App\Models\Bus;
use Illuminate\Contracts\Support\Arrayable;
use League\Fractal\Resource\ResourceInterface;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms bus to display on buses page.
 */
class BusTransformer extends BaseTransformer
{
    /**
     * Include resources without needing it to be requested.
     *
     * @var string[]
     */
    protected $defaultIncludes = [
        'route',
        'company',
    ];

    /**
     * Transforms bus to display on buses page.
     *
     * @param Arrayable $model Model to transform
     *
     * @return string[]
     *
     * @throws TransformTypeMismatchException
     */
    public function transform(Arrayable $model): array
    {
        if (!$model instanceof Bus) {
            throw new TransformTypeMismatchException($this, Bus::class, get_class($model));
        }

        return $this->transformModel($model);
    }

    /**
     * Transforms bus into appropriate format.
     *
     * @param Bus $bus Bus to transform
     *
     * @return string[]
     */
    protected function transformModel(Bus $bus): array
    {
        return [
            'id' => $bus->id,
            'first_name' => $bus->model_name,
            'last_name' => $bus->state_number,
            'email' => $bus->company_id,
            'role_id' => $bus->route_id,
            'company_id' => $bus->company_id,
            // possible related records count
            'drivers_count' => $bus->getAttribute('drivers_count'),
            'validators_count' => $bus->getAttribute('validators_count'),
        ];
    }

    /**
     * Includes role into transformed response.
     *
     * @param Bus $bus Bus to retrieve role details
     *
     * @return ResourceInterface
     */
    protected function includeRole(Bus $bus): ResourceInterface
    {
        return $this->item($bus->role, app(RoleTransformer::class));
    }

    /**
     * Includes company into transformed response.
     *
     * @param Bus $bus Bus to retrieve company details
     *
     * @return ResourceInterface
     */
    protected function includeCompany(Bus $bus): ResourceInterface
    {
        if (!$bus->company) {
            return $this->null();
        }

        return $this->item($bus->company, app(CompanyTransformer::class));
    }

    /**
     * Includes route into transformed response.
     *
     * @param Bus $bus Bus to retrieve route details
     *
     * @return ResourceInterface
     */
    protected function includeRoute(Bus $bus): ResourceInterface
    {
        if (!$bus->route) {
            return $this->null();
        }

        return $this->item($bus->route, app(RouteTransformer::class));
    }
}
