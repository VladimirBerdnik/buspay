<?php

namespace App\Http\Transformers\Api;

use App\Models\Tariff;
use Illuminate\Contracts\Support\Arrayable;
use League\Fractal\Resource\ResourceInterface;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms tariff to display on tariff page.
 */
class TariffTransformer extends BaseTransformer
{
    /**
     * Include resources without needing it to be requested.
     *
     * @var string[]
     */
    protected $defaultIncludes = [
        'tariffFares',
    ];

    /**
     * Transforms tariff to display on tariff page.
     *
     * @param Arrayable $model Model to transform
     *
     * @return string[]
     *
     * @throws TransformTypeMismatchException
     */
    public function transform(Arrayable $model): array
    {
        if (!$model instanceof Tariff) {
            throw new TransformTypeMismatchException($this, Tariff::class, get_class($model));
        }

        return $this->transformModel($model);
    }

    /**
     * Transforms tariff into appropriate format.
     *
     * @param Tariff $tariff Tariff to transform
     *
     * @return string[]
     */
    protected function transformModel(Tariff $tariff): array
    {
        return [
            'id' => $tariff->id,
            'name' => $tariff->name,
        ];
    }

    /**
     * Includes role into transformed response.
     *
     * @param Tariff $tariff Tariff to retrieve tariff fares details
     *
     * @return ResourceInterface
     */
    protected function includeTariffFares(Tariff $tariff): ResourceInterface
    {
        return $this->collection($tariff->tariffFares, app(TariffFareTransformer::class));
    }
}
