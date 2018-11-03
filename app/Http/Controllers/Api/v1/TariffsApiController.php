<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\Services\TariffService;
use App\Models\Tariff;
use App\Models\TariffFare;
use App\Models\TariffPeriod;
use Dingo\Api\Http\Response;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\Transformers\IDataTransformer;

/**
 * Tariffs requests API controller.
 */
class TariffsApiController extends BaseApiController
{
    /**
     * Tariffs business-logic service.
     *
     * @var TariffService
     */
    private $tariffService;

    /**
     * Tariffs requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param TariffService $tariffService Tariffs business logic service
     */
    public function __construct(IDataTransformer $transformer, TariffService $tariffService)
    {
        parent::__construct($transformer);
        $this->tariffService = $tariffService;
    }

    /**
     * Returns tariffs list.
     *
     * @param TariffPeriod $tariffPeriod Period of tariff fares activity to return
     *
     * @return Response
     *
     * @throws InvalidEnumValueException In case of invalid order direction usage
     */
    public function index(TariffPeriod $tariffPeriod): Response
    {
        return $this->response->collection(
            $this->tariffService->getWith(
                ['tariffFares' => [TariffFare::TARIFF_PERIOD_ID => $tariffPeriod->getKey()]],
                [],
                [],
                new SortOptions(Tariff::ID)
            ),
            $this->transformer
        );
    }
}
