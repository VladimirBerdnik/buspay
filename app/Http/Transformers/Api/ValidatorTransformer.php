<?php

namespace App\Http\Transformers\Api;

use App\Models\Validator;
use Illuminate\Contracts\Support\Arrayable;
use League\Fractal\Resource\ResourceInterface;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms validator to display on validators page.
 */
class ValidatorTransformer extends BaseTransformer
{
    public const INCLUDE_BUS = 'bus';

    /**
     * Include resources without needing it to be requested.
     *
     * @var string[]
     */
    protected $defaultIncludes = [
        self::INCLUDE_BUS,
    ];

    /**
     * Transforms bus to display details.
     *
     * @var BusTransformer
     */
    private $busTransformer;

    /**
     * Transforms validator to display on validators page.
     *
     * @param BusTransformer $busTransformer Transforms bus to display details
     */
    public function __construct(BusTransformer $busTransformer)
    {
        $this->busTransformer = $busTransformer;
        $this->busTransformer->setDefaultIncludes([]);
    }

    /**
     * Transforms validator to display on validators page.
     *
     * @param Arrayable $model Model to transform
     *
     * @return string[]
     *
     * @throws TransformTypeMismatchException
     */
    public function transform(Arrayable $model): array
    {
        if (!$model instanceof Validator) {
            throw new TransformTypeMismatchException($this, Validator::class, get_class($model));
        }

        return $this->transformModel($model);
    }

    /**
     * Transforms validator into appropriate format.
     *
     * @param Validator $validator Validator to transform
     *
     * @return string[]
     */
    protected function transformModel(Validator $validator): array
    {
        return [
            Validator::ID => $validator->id,
            Validator::SERIAL_NUMBER => $validator->serial_number,
            Validator::MODEL => $validator->model,
            Validator::BUS_ID => $validator->bus_id,
        ];
    }

    /**
     * Transforms bus to display details.
     *
     * @return BusTransformer
     */
    public function getBusTransformer(): BusTransformer
    {
        return $this->busTransformer;
    }

    /**
     * Includes bus into transformed response.
     *
     * @param Validator $validator Validator to retrieve bus details
     *
     * @return ResourceInterface
     */
    protected function includeBus(Validator $validator): ResourceInterface
    {
        if (!$validator->bus) {
            return $this->null();
        }

        return $this->item($validator->bus, $this->busTransformer);
    }
}
