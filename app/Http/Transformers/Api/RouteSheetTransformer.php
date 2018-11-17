<?php

namespace App\Http\Transformers\Api;

use App\Models\RouteSheet;
use Illuminate\Contracts\Support\Arrayable;
use League\Fractal\Resource\ResourceInterface;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms route sheet to display on route sheets page.
 */
class RouteSheetTransformer extends BaseTransformer
{
    public const INCLUDE_COMPANY = 'company';
    public const INCLUDE_BUS = 'bus';
    public const INCLUDE_DRIVER = 'driver';
    public const INCLUDE_ROUTE = 'route';

    /**
     * Include resources without needing it to be requested.
     *
     * @var string[]
     */
    protected $defaultIncludes = [
        self::INCLUDE_COMPANY,
        self::INCLUDE_BUS,
        self::INCLUDE_DRIVER,
        self::INCLUDE_ROUTE,
    ];

    /**
     * Transforms company to display as driver relation.
     *
     * @var CompanyTransformer
     */
    private $companyTransformer;

    /**
     * Transforms bus to display details.
     *
     * @var BusTransformer
     */
    private $busTransformer;

    /**
     * Transforms driver to display details.
     *
     * @var DriverTransformer
     */
    private $driverTransformer;

    /**
     * Transforms route to display details.
     *
     * @var RouteTransformer
     */
    private $routeTransformer;

    /**
     * Transforms route sheet to display on route sheets page.
     *
     * @param CompanyTransformer $companyTransformer Transforms company to display details
     * @param BusTransformer $busTransformer Transforms bus to display details
     * @param DriverTransformer $driverTransformer Transforms route sheet to display details
     * @param RouteTransformer $routeTransformer Transforms route to display details
     */
    public function __construct(
        CompanyTransformer $companyTransformer,
        BusTransformer $busTransformer,
        DriverTransformer $driverTransformer,
        RouteTransformer $routeTransformer
    ) {
        $this->companyTransformer = $companyTransformer;
        $this->companyTransformer->setDefaultIncludes([]);

        $this->busTransformer = $busTransformer;
        $this->busTransformer->setDefaultIncludes([]);

        $this->driverTransformer = $driverTransformer;
        $this->driverTransformer->setDefaultIncludes([]);

        $this->routeTransformer = $routeTransformer;
        $this->routeTransformer->setDefaultIncludes([]);
    }

    /**
     * Transforms route sheet to display on route sheets page.
     *
     * @param Arrayable $model Model to transform
     *
     * @return string[]
     *
     * @throws TransformTypeMismatchException
     */
    public function transform(Arrayable $model): array
    {
        if (!$model instanceof RouteSheet) {
            throw new TransformTypeMismatchException($this, RouteSheet::class, get_class($model));
        }

        return $this->transformModel($model);
    }

    /**
     * Transforms route sheet into appropriate format.
     *
     * @param RouteSheet $routeSheet Route sheet to transform
     *
     * @return string[]
     */
    protected function transformModel(RouteSheet $routeSheet): array
    {
        return [
            RouteSheet::ID => $routeSheet->id,
            RouteSheet::COMPANY_ID => $routeSheet->company_id,
            RouteSheet::ROUTE_ID => $routeSheet->route_id,
            RouteSheet::BUS_ID => $routeSheet->bus_id,
            RouteSheet::DRIVER_ID => $routeSheet->driver_id,
            RouteSheet::ACTIVE_FROM => $routeSheet->active_from->toIso8601String(),
            RouteSheet::ACTIVE_TO => $routeSheet->active_to ? $routeSheet->active_to->toIso8601String() : null,
        ];
    }

    /**
     * Includes company into transformed response.
     *
     * @param RouteSheet $routeSheet Route sheet to retrieve company details
     *
     * @return ResourceInterface
     */
    protected function includeCompany(RouteSheet $routeSheet): ResourceInterface
    {
        if (!$routeSheet->company) {
            return $this->null();
        }

        return $this->item($routeSheet->company, $this->companyTransformer);
    }

    /**
     * Includes bus into transformed response.
     *
     * @param RouteSheet $routeSheet Route sheet to retrieve bus details
     *
     * @return ResourceInterface
     */
    protected function includeBus(RouteSheet $routeSheet): ResourceInterface
    {
        if (!$routeSheet->bus) {
            return $this->null();
        }

        return $this->item($routeSheet->bus, $this->busTransformer);
    }

    /**
     * Includes route into transformed response.
     *
     * @param RouteSheet $routeSheet Route sheet to retrieve route details
     *
     * @return ResourceInterface
     */
    protected function includeRoute(RouteSheet $routeSheet): ResourceInterface
    {
        if (!$routeSheet->route) {
            return $this->null();
        }

        return $this->item($routeSheet->route, $this->routeTransformer);
    }

    /**
     * Includes driver into transformed response.
     *
     * @param RouteSheet $routeSheet Route sheet to retrieve driver details
     *
     * @return ResourceInterface
     */
    protected function includeDriver(RouteSheet $routeSheet): ResourceInterface
    {
        if (!$routeSheet->driver) {
            return $this->null();
        }

        return $this->item($routeSheet->driver, $this->driverTransformer);
    }
}
