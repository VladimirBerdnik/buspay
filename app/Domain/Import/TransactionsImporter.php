<?php

namespace App\Domain\Import;

use App\Domain\Dto\TransactionData;
use App\Domain\EntitiesServices\CardEntityService;
use App\Domain\EntitiesServices\TariffEntityService;
use App\Domain\EntitiesServices\TransactionEntityService;
use App\Domain\EntitiesServices\ValidatorEntityService;
use App\Domain\Import\Dto\ExternalTransactionData;
use App\Domain\Import\Exceptions\Integrity\NoCardForTransactionException;
use App\Domain\Import\Exceptions\Integrity\NoTariffForTransactionException;
use App\Domain\Import\Exceptions\Integrity\NoValidatorForTransactionException;
use App\Domain\Import\Exceptions\Integrity\TooManyTransactionWithExternalIdException;
use App\Domain\Import\Exceptions\Integrity\TransactionMismatchException;
use App\Domain\Services\CardAuthorizationService;
use App\Extensions\ErrorsReporter;
use App\Models\Card;
use App\Models\Tariff;
use App\Models\Transaction;
use App\Models\Validator;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Log;
use Throwable;

/**
 * Transaction importer. Allows to import transaction records from external storage.
 */
class TransactionsImporter extends ExternalEntitiesImportService
{
    /**
     * Transaction entity service.
     *
     * @var TransactionEntityService
     */
    private $transactionEntityService;

    /**
     * Card entities service.
     *
     * @var CardEntityService
     */
    private $cardEntityService;

    /**
     * Validator entity service.
     *
     * @var ValidatorEntityService
     */
    private $validatorEntityService;

    /**
     * Tariff entity service.
     *
     * @var TariffEntityService
     */
    private $tariffEntityService;

    /**
     * Service that can process card authorization.
     *
     * @var CardAuthorizationService
     */
    private $cardAuthorizationService;

    /**
     * Transaction importer. Allows to import transaction records from external storage.
     *
     * @param ConnectionInterface $connection External storage connection
     * @param ErrorsReporter $errorsReporter Errors and messages reporter
     * @param TransactionEntityService $transactionEntityService Transaction entity service
     * @param CardEntityService $cardEntityService Card entities service
     * @param ValidatorEntityService $validatorEntityService Validator entity service
     * @param TariffEntityService $tariffEntityService Tariff entity service
     * @param CardAuthorizationService $cardAuthorizationService Service that can process card authorization
     */
    public function __construct(
        ConnectionInterface $connection,
        ErrorsReporter $errorsReporter,
        TransactionEntityService $transactionEntityService,
        CardEntityService $cardEntityService,
        ValidatorEntityService $validatorEntityService,
        TariffEntityService $tariffEntityService,
        CardAuthorizationService $cardAuthorizationService
    ) {
        parent::__construct($connection, $errorsReporter);
        $this->transactionEntityService = $transactionEntityService;
        $this->cardEntityService = $cardEntityService;
        $this->validatorEntityService = $validatorEntityService;
        $this->tariffEntityService = $tariffEntityService;
        $this->cardAuthorizationService = $cardAuthorizationService;
    }

    /**
     * Returns chunk size for collections of items to import.
     *
     * @return integer
     */
    private function getChunkSize(): int
    {
        return config('import.transaction.importChunkSize') ?? 50;
    }

    /**
     * Returns days interval within transaction synchronization should be performed.
     *
     * @return integer
     */
    private function getVerifyingDaysInterval(): int
    {
        return config('import.transaction.synchronisationDaysInterval') ?? 0;
    }

    /**
     * Performs transaction import from external transaction storage.
     *
     * @param Carbon|null $importFrom When passed then starts import from given date
     */
    public function import(?Carbon $importFrom): void
    {
        $startTime = time();

        $importFrom = $importFrom ?? Carbon::today()->subDays($this->getVerifyingDaysInterval());

        $this->importData($importFrom);

        $importDuration = time() - $startTime;

        Log::info("Transactions import took ~{$importDuration} second(s)");
    }

    /**
     * Import data from external storage.
     *
     * @param Carbon $importFrom Start import from given date
     */
    private function importData(Carbon $importFrom): void
    {
        Log::debug("Transactions details import process started");

        $this->getConnection()
            ->table('transactions')
            ->where(ExternalTransactionData::DATE, '>=', $importFrom)
            ->orderBy(ExternalTransactionData::ID)
            ->chunk($this->getChunkSize(), function (Collection $items, int $pageNumber): void {
                Log::debug("Transactions chunk #{$pageNumber} retrieved. Chunk size: " . $items->count());
                $this->importChunk($items);
            });

        Log::debug('Transactions details import process finished.');
    }

    /**
     * Performs import of chunk of external transaction details.
     *
     * @param Collection|object[] $items Collection of items to import
     *
     * @throws Throwable
     */
    private function importChunk(Collection $items): void
    {
        foreach ($items as $index => $item) {
            try {
                Log::debug("Prepare import transaction #{$index} with details " . json_encode($item));

                $externalTransactionData = new ExternalTransactionData((array)$item);

                $transaction = $this->importExternalTransaction($externalTransactionData);

                if ($transaction) {
                    Log::debug(
                        "Transaction with external id [{$transaction->external_id}] " .
                        "synchronized as [ID={$transaction->id}]"
                    );
                } else {
                    Log::debug("Transaction with external id [{$externalTransactionData->id}] wasn't synchronized");
                }
            } catch (Exception $e) {
                Log::error("Import transaction error occurred: {$e->getMessage()}");
            }
        }
    }

    /**
     * Performs external transaction data import.
     *
     * @param ExternalTransactionData $externalTransactionData Details of external transaction to import
     *
     * @return Transaction|null
     *
     * @throws Throwable
     */
    private function importExternalTransaction(ExternalTransactionData $externalTransactionData): ?Transaction
    {
        Log::debug("Search authorized card with number {$externalTransactionData->card_number}");

        /**
         * Card that was authorized.
         *
         * @var Card $authorizedCard
         */
        $authorizedCard = $this->cardEntityService->findWhere([
            Card::CARD_NUMBER => $externalTransactionData->card_number,
        ]);

        if (!$authorizedCard) {
            throw new NoCardForTransactionException($externalTransactionData->card_number);
        }

        Log::debug("Found authorized card with ID {$authorizedCard->id}");

        /**
         * Validator that authorizes card.
         *
         * @var Validator $validator
         */
        $validator = $this->validatorEntityService->findWhere([
            Validator::EXTERNAL_ID => $externalTransactionData->validator_id,
        ]);

        if (!$validator) {
            throw new NoValidatorForTransactionException($externalTransactionData->validator_id);
        }

        Log::debug("Found authorized validator with ID {$validator->id}");

        if ($externalTransactionData->tariff_id) {
            /**
             * Tariff on which card was authorized.
             *
             * @var Tariff $tariff
             */
            $tariff = $this->tariffEntityService->findWhere([
                Tariff::ID => $externalTransactionData->tariff_id,
            ]);

            if (!$tariff) {
                throw new NoTariffForTransactionException($externalTransactionData->tariff_id);
            }

            Log::debug("Found tariff on which card was authorized {$tariff->id}");
        } else {
            $tariff = null;
        }

        $transactionData = new TransactionData([
            TransactionData::CARD_ID => $authorizedCard->id,
            TransactionData::VALIDATOR_ID => $validator->id,
            TransactionData::TARIFF_ID => $tariff ? $tariff->id : null,
            TransactionData::AUTHORIZED_AT => $externalTransactionData->date,
            TransactionData::AMOUNT => $externalTransactionData->sum,
            TransactionData::EXTERNAL_ID => $externalTransactionData->id,
        ]);

        Log::debug("Search transaction with ID {$transactionData->external_id} to check");

        $matchedItems = $this->transactionEntityService->getWhere([
            Transaction::EXTERNAL_ID => $transactionData->external_id,
        ]);

        if ($matchedItems->count() > 1) {
            throw new TooManyTransactionWithExternalIdException($transactionData->external_id);
        }

        if ($matchedItems->count() === 1) {
            /**
             * Found transaction to check.
             *
             * @var Transaction $transaction
             */
            $transaction = $matchedItems->first();

            Log::debug("Transaction [{$transaction->id}] found");

            if ($transaction->card_id !== $transactionData->card_id ||
                $transaction->amount !== $transactionData->amount ||
                !$transaction->authorized_at->eq(Carbon::parse($transactionData->authorized_at)) ||
                $transaction->tariff_id !== $transactionData->tariff_id ||
                $transaction->validator_id !== $transactionData->validator_id ||
                $transaction->external_id !== $transactionData->external_id
            ) {
                throw new TransactionMismatchException($transactionData->external_id);
            }
        } else {
            Log::debug('No existing transaction found to update. Trying to create');

            $transaction = $this->createTransaction($transactionData);
        }

        return $transaction;
    }

    /**
     * Creates new transaction with imported parameters.
     *
     * @param TransactionData $transactionData Transaction details to create new transaction
     *
     * @return Transaction|null
     *
     * @throws Throwable
     */
    private function createTransaction(TransactionData $transactionData): ?Transaction
    {
        try {
            return DB::transaction(function () use ($transactionData): Transaction {
                $transaction = $this->transactionEntityService->store($transactionData);
                $this->cardAuthorizationService->processCardAuthorization($transaction);

                return $transaction;
            });
        } catch (Exception $e) {
            $this->getErrorsReporter()->reportException($e, $transactionData->toArray());

            $payload = [];
            if ($e instanceof ValidationException) {
                $payload = $e->errors();
            }

            Log::error("Create imported transaction error: {$e->getMessage()}", $payload);
        }

        return null;
    }
}
