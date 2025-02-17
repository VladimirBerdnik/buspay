<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\TariffEntityService;
use App\Domain\Enums\Abilities;
use App\Models\Tariff;
use App\Models\TariffFare;
use App\Models\TariffPeriod;
use Dingo\Api\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\Transformers\IDataTransformer;

/**
 * Tariffs requests API controller.
 */
class TariffFaresApiController extends BaseApiController
{
    /**
     * Tariffs entity service.
     *
     * @var TariffEntityService
     */
    private $tariffService;

    /**
     * Tariffs requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param TariffEntityService $tariffService Tariffs entity service
     */
    public function __construct(IDataTransformer $transformer, TariffEntityService $tariffService)
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
     * @throws AuthorizationException
     */
    public function index(TariffPeriod $tariffPeriod): Response
    {
        $this->authorize(Abilities::GET, new TariffFare());
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
