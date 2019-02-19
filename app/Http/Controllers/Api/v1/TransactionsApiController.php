<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\Dto\Filters\TransactionsFilterData;
use App\Domain\Enums\Abilities;
use App\Http\Requests\Api\PaginatedSortedFilteredListRequest;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Dingo\Api\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\Transformers\IDataTransformer;

/**
 * Transactions requests API controller.
 */
class TransactionsApiController extends BaseApiController
{
    /**
     * Transactions records storage.
     *
     * @var TransactionRepository
     */
    private $transactionRepository;

    /**
     * Transactions requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param TransactionRepository $transactionRepository Transactions records storage
     */
    public function __construct(IDataTransformer $transformer, TransactionRepository $transactionRepository)
    {
        parent::__construct($transformer);
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Returns transactions list.
     *
     * @param PaginatedSortedFilteredListRequest $request Request with parameters to retrieve paginated sorted filtered
     *     list of items
     *
     * @return Response
     *
     * @throws InvalidEnumValueException
     * @throws AuthorizationException
     */
    public function index(PaginatedSortedFilteredListRequest $request): Response
    {
        $this->authorize(Abilities::GET, new Transaction());

        $filters = $request->getFilters([]);
        $companyId = $this->singleCompanyUser()
            ? $this->user->company_id
            : $filters[TransactionsFilterData::COMPANY_ID] ?? null;

        $transactionsFilter = new TransactionsFilterData([
            TransactionsFilterData::SEARCH_STRING => $request->getSearchString(),
            TransactionsFilterData::AUTHORIZED_FROM => $request->activeFrom(),
            TransactionsFilterData::AUTHORIZED_TO => $request->activeTo(),
            TransactionsFilterData::COMPANY_ID => $companyId,
            TransactionsFilterData::CARD_TYPE_ID => $filters[TransactionsFilterData::CARD_TYPE_ID] ?? null,
            TransactionsFilterData::TARIFF_ID => $filters[TransactionsFilterData::TARIFF_ID] ?? null,
            TransactionsFilterData::VALIDATOR_ID => $filters[TransactionsFilterData::VALIDATOR_ID] ?? null,
            TransactionsFilterData::ROUTE_ID => $filters[TransactionsFilterData::ROUTE_ID] ?? null,
            TransactionsFilterData::BUS_ID => $filters[TransactionsFilterData::BUS_ID] ?? null,
            TransactionsFilterData::DRIVER_ID => $filters[TransactionsFilterData::DRIVER_ID] ?? null,
        ]);

        // TODO 'validator.bus.route' and 'validator.bus.company' can't be loaded so easy. Need to get by dates

        return $this->response->paginator(
            $this->transactionRepository->getFilteredPageWith(
                $request->getPagingInfo(),
                [
                    'card.cardType',
                    'tariff',
                    'validator',
                    'routeSheet.company',
                    'routeSheet.route',
                    'routeSheet.bus',
                ],
                [],
                $transactionsFilter,
                $request->getSortOptions()
            ),
            $this->transformer
        );
    }
}
