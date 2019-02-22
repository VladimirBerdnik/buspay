<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\Dto\Filters\TransactionsFilterData;
use App\Domain\Enums\Abilities;
use App\Domain\Export\TransactionsExporter;
use App\Http\Requests\Api\PaginatedSortedFilteredListRequest;
use App\Http\Transformers\Api\ImpersonalCardTransformer;
use App\Http\Transformers\Api\TransactionTransformer;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Dingo\Api\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\Transformers\IDataTransformer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Transactions requests API controller.
 */
class TransactionsApiController extends BaseApiController
{
    /**
     * Handled by controller entities default transformer.
     *
     * @var IDataTransformer|TransactionTransformer
     */
    protected $transformer;

    /**
     * Transactions records storage.
     *
     * @var TransactionRepository
     */
    private $transactionRepository;

    /**
     * Transforms card to display impersonated card details.
     *
     * @var ImpersonalCardTransformer
     */
    private $impersonalCardTransformer;

    /**
     * Transactions entities exporter.
     *
     * @var TransactionsExporter
     */
    private $transactionsExporter;

    /**
     * Relations that should be automatically loaded with list of transactions.
     *
     * @var string[]
     */
    private $preloadedRelations = [
        'card.cardType',
        'tariff',
        'validator',
        'routeSheet.company',
        'routeSheet.route',
        'routeSheet.bus',
    ];

    /**
     * Transactions requests API controller.
     *
     * @param IDataTransformer $transformer Handled by controller entities default transformer
     * @param ImpersonalCardTransformer $impersonalCardTransformer Transforms card to display impersonated card details
     * @param TransactionRepository $transactionRepository Transactions records storage
     * @param TransactionsExporter $transactionsExporter Transactions entities exporter
     */
    public function __construct(
        IDataTransformer $transformer,
        ImpersonalCardTransformer $impersonalCardTransformer,
        TransactionRepository $transactionRepository,
        TransactionsExporter $transactionsExporter
    ) {
        parent::__construct($transformer);
        $this->transactionRepository = $transactionRepository;
        $this->impersonalCardTransformer = $impersonalCardTransformer;
        $this->transactionsExporter = $transactionsExporter;
    }

    /**
     * Returns transactions filter data that should be applied against list of transactions.
     *
     * @param PaginatedSortedFilteredListRequest $request Transactions list request with filtering details
     *
     * @return TransactionsFilterData
     */
    private function getTransactionsFilterData(PaginatedSortedFilteredListRequest $request): TransactionsFilterData
    {
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

        return $transactionsFilter;
    }

    /**
     * Sets card transformer according to card details access policy. Not all users can see full card data.
     */
    private function setAppropriateTransactionCardTransformer(): void
    {
        if ($this->can(Abilities::SHOW_TRANSACTION_CARD, new Transaction())) {
            return;
        }

        $this->transformer->setCardTransformer($this->impersonalCardTransformer);
        $this->impersonalCardTransformer->setDefaultIncludes([ImpersonalCardTransformer::INCLUDE_CARD_TYPE]);
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

        $this->setAppropriateTransactionCardTransformer();

        $transactionsFilter = $this->getTransactionsFilterData($request);

        return $this->response->paginator(
            $this->transactionRepository->getFilteredPageWith(
                $request->getPagingInfo(),
                $this->preloadedRelations,
                [],
                $transactionsFilter,
                $request->getSortOptions()
            ),
            $this->transformer
        );
    }

    /**
     * Returns transactions list.
     *
     * @param PaginatedSortedFilteredListRequest $request Request with parameters to retrieve paginated sorted filtered
     *     list of items
     *
     * @return BinaryFileResponse
     *
     * @throws InvalidEnumValueException
     * @throws AuthorizationException
     */
    public function export(PaginatedSortedFilteredListRequest $request): BinaryFileResponse
    {
        $this->authorize(Abilities::GET, new Transaction());

        $this->setAppropriateTransactionCardTransformer();

        $transactionsFilter = $this->getTransactionsFilterData($request);

        $onlyDriverCardsNumbersVisible = $this->can(Abilities::SHOW_TRANSACTION_CARD, new Transaction());

        $exportedFileName = $this->transactionsExporter->export(
            $transactionsFilter,
            $request->getSortOptions(),
            !$onlyDriverCardsNumbersVisible
        );

        return new BinaryFileResponse($exportedFileName);
    }
}
