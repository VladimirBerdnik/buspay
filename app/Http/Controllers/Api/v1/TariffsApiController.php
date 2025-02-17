<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\EntitiesServices\TariffEntityService;
use App\Domain\Enums\Abilities;
use App\Models\Tariff;
use Dingo\Api\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\Transformers\IDataTransformer;

/**
 * Tariffs requests API controller.
 */
class TariffsApiController extends BaseApiController
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
     * @return Response
     *
     * @throws InvalidEnumValueException In case of invalid order direction usage
     * @throws AuthorizationException
     */
    public function index(): Response
    {
        $this->authorize(Abilities::GET, new Tariff());

        $this->transformer->setDefaultIncludes([]);

        return $this->response->collection(
            $this->tariffService->getWith(
                [],
                [],
                [],
                new SortOptions(Tariff::ID)
            ),
            $this->transformer
        );
    }
}
