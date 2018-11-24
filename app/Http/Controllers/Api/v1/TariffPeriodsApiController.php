<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\TariffPeriodService;
use App\Models\TariffPeriod;
use Dingo\Api\Http\Response;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Enums\OrderDirections;
use Saritasa\Transformers\IDataTransformer;

/**
 * Tariff periods requests API controller.
 */
class TariffPeriodsApiController extends BaseApiController
{
    /**
     * Tariff periods business-logic service.
     *
     * @var TariffPeriodService
     */
    private $tariffPeriodService;

    /**
     * Tariff periods requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param TariffPeriodService $tariffPeriodService Tariff periods business logic service
     */
    public function __construct(IDataTransformer $transformer, TariffPeriodService $tariffPeriodService)
    {
        parent::__construct($transformer);
        $this->tariffPeriodService = $tariffPeriodService;
    }

    /**
     * Returns tariff periods list.
     *
     * @return Response
     *
     * @throws InvalidEnumValueException In case of invalid order direction usage
     */
    public function index(): Response
    {
        return $this->response->collection(
            $this->tariffPeriodService->getWith(
                [],
                [],
                [],
                new SortOptions(TariffPeriod::ACTIVE_FROM, OrderDirections::DESC)
            ),
            $this->transformer
        );
    }
}
