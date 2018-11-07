<?php

namespace App\Http\Transformers\Api;

use App\Models\Tariff;
use App\Models\TariffFare;
use Illuminate\Contracts\Support\Arrayable;
use League\Fractal\Resource\ResourceInterface;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms tariff to display on tariffs page.
 */
class TariffTransformer extends BaseTransformer
{
    public const INCLUDE_TARIFF_FARES = 'tariffFares';

    /**
     * Include resources without needing it to be requested.
     *
     * @var string[]
     */
    protected $defaultIncludes = [
        self::INCLUDE_TARIFF_FARES,
    ];

    /**
     * Transforms tariff fares to display as tariff relation.
     *
     * @var TariffFareTransformer
     */
    private $tariffFareTransformer;

    /**
     * Transforms tariff to display on tariffs page.
     *
     * @param TariffFareTransformer $tariffFareTransformer Transforms tariff fares to display as tariff relation
     */
    public function __construct(TariffFareTransformer $tariffFareTransformer)
    {
        $this->tariffFareTransformer = $tariffFareTransformer;

        $this->tariffFareTransformer->setDefaultIncludes([]);
    }

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
        $tariffFares = $tariff->tariffFares->map(
            function (TariffFare $tariffFare) {
                return $this->tariffFareTransformer->transform($tariffFare);
            }
        );

        return $this->primitive($tariffFares);
    }
}
