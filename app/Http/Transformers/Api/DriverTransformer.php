<?php

namespace App\Http\Transformers\Api;

use App\Models\Driver;
use Illuminate\Contracts\Support\Arrayable;
use League\Fractal\Resource\ResourceInterface;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms driver to display on drivers page.
 */
class DriverTransformer extends BaseTransformer
{
    public const INCLUDE_COMPANY = 'company';
    public const INCLUDE_CARD = 'card';
    public const INCLUDE_BUS = 'bus';

    /**
     * Include resources without needing it to be requested.
     *
     * @var string[]
     */
    protected $defaultIncludes = [
        self::INCLUDE_COMPANY,
        self::INCLUDE_CARD,
        self::INCLUDE_BUS,
    ];

    /**
     * Transforms company to display as driver relation.
     *
     * @var CompanyTransformer
     */
    private $companyTransformer;

    /**
     * Transforms card to display details.
     *
     * @var CardTransformer
     */
    private $cardTransformer;

    /**
     * Transforms bus to display details.
     *
     * @var BusTransformer
     */
    private $busTransformer;

    /**
     * Transforms driver to display on drivers page.
     *
     * @param CompanyTransformer $companyTransformer Transforms company to display as driver relation
     * @param CardTransformer $cardTransformer Transforms card to display details
     * @param BusTransformer $busTransformer Transforms bus to display details
     */
    public function __construct(
        CompanyTransformer $companyTransformer,
        CardTransformer $cardTransformer,
        BusTransformer $busTransformer
    ) {
        $this->companyTransformer = $companyTransformer;
        $this->companyTransformer->setDefaultIncludes([]);

        $this->cardTransformer = $cardTransformer;
        $this->cardTransformer->setDefaultIncludes([CardTransformer::INCLUDE_CARD_TYPE]);

        $this->busTransformer = $busTransformer;
        $this->busTransformer->setDefaultIncludes([]);
    }

    /**
     * Transforms driver to display on drivers page.
     *
     * @param Arrayable $model Model to transform
     *
     * @return string[]
     *
     * @throws TransformTypeMismatchException
     */
    public function transform(Arrayable $model): array
    {
        if (!$model instanceof Driver) {
            throw new TransformTypeMismatchException($this, Driver::class, get_class($model));
        }

        return $this->transformModel($model);
    }

    /**
     * Transforms driver into appropriate format.
     *
     * @param Driver $driver Driver to transform
     *
     * @return string[]
     */
    protected function transformModel(Driver $driver): array
    {
        return [
            Driver::ID => $driver->id,
            Driver::FULL_NAME => $driver->full_name,
            Driver::BUS_ID => $driver->bus_id,
            Driver::CARD_ID => $driver->card_id,
            Driver::COMPANY_ID => $driver->company_id,
        ];
    }

    /**
     * Includes company into transformed response.
     *
     * @param Driver $driver Driver to retrieve company details
     *
     * @return ResourceInterface
     */
    protected function includeCompany(Driver $driver): ResourceInterface
    {
        if (!$driver->company) {
            return $this->null();
        }

        return $this->item($driver->company, $this->companyTransformer);
    }

    /**
     * Includes card into transformed response.
     *
     * @param Driver $driver Driver to retrieve card details
     *
     * @return ResourceInterface
     */
    protected function includeCard(Driver $driver): ResourceInterface
    {
        if (!$driver->card) {
            return $this->null();
        }

        return $this->item($driver->card, $this->cardTransformer);
    }

    /**
     * Includes bus into transformed response.
     *
     * @param Driver $driver Driver to retrieve bus details
     *
     * @return ResourceInterface
     */
    protected function includeBus(Driver $driver): ResourceInterface
    {
        if (!$driver->bus) {
            return $this->null();
        }

        return $this->item($driver->bus, $this->busTransformer);
    }
}
