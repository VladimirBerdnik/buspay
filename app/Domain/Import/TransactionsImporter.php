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
use Saritasa\LaravelRepositories\DTO\SortOptions;
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
     * @param TransactionEntityService $transactionEntityService Transaction entity service
     * @param CardEntityService $cardEntityService Card entities service
     * @param ValidatorEntityService $validatorEntityService Validator entity service
     * @param TariffEntityService $tariffEntityService Tariff entity service
     * @param CardAuthorizationService $cardAuthorizationService Service that can process card authorization
     */
    public function __construct(
        ConnectionInterface $connection,
        TransactionEntityService $transactionEntityService,
        CardEntityService $cardEntityService,
        ValidatorEntityService $validatorEntityService,
        TariffEntityService $tariffEntityService,
        CardAuthorizationService $cardAuthorizationService
    ) {
        parent::__construct($connection);
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

        $this->verifyTransactions($importFrom);

        $verifyDuration = time() - $startTime;

        $this->importData($importFrom);

        $importDuration = time() - $startTime - $verifyDuration;

        Log::info(
            "Transactions verifying took ~{$verifyDuration} second(s), import took ~{$importDuration} second(s)"
        );
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
            ->table('transaction')
            ->where(ExternalTransactionData::DATE, '>=', $importFrom)
            ->orderBy(ExternalTransactionData::ID)
            ->chunk($this->getChunkSize(), function (Collection $items, int $pageNumber): void {
                Log::debug("Transactions chunk #{$pageNumber} retrieved. Chunk size: " . $items->count());
                $this->importChunk($items);
            });

        Log::debug('Updated transactions details import process finished.');
    }

    /**
     * Verifies existing transactions that they are exists in external storage.
     *
     * @param Carbon $verifyFrom Date from which need to verify transaction records
     */
    private function verifyTransactions(Carbon $verifyFrom): void
    {
        Log::debug('Verify existing transactions import process started.');

        try {
            $verifyTo = Carbon::today();
            $where = [
                [Transaction::AUTHORIZED_AT, '>=', $verifyFrom],
                [Transaction::AUTHORIZED_AT, '<=', $verifyTo],
            ];
            $this->transactionEntityService->chunkWith(
                [],
                [],
                $where,
                new SortOptions(Transaction::AUTHORIZED_AT),
                $this->getChunkSize(),
                function (Collection $items, int $pageNumber) use (&$localStorageTransactionsIdentifiers): void {
                    Log::debug(
                        "Transactions to verify chunk #{$pageNumber} retrieved. Chunk size: " . $items->count()
                    );

                    // This list of identifiers are exists in our storage
                    $localStorageTransactionsIdentifiers = $items->pluck(Transaction::EXTERNAL_ID);

                    // Let's retrieve list ov transaction with same identifiers from external storage
                    $externalStorageTransactionsIdentifiers = $this->getConnection()
                        ->table('transaction')
                        ->whereIn(ExternalTransactionData::ID, $localStorageTransactionsIdentifiers)
                        ->get()
                        ->pluck(ExternalTransactionData::ID);

                    // Now let's see difference between local and external transaction
                    $notExistingIdentifiers = $localStorageTransactionsIdentifiers
                        ->diff($externalStorageTransactionsIdentifiers);

                    if ($notExistingIdentifiers->isNotEmpty()) {
                        foreach ($notExistingIdentifiers as $notExistingIdentifier) {
                            Log::error(
                                "Transaction with external identifier [{$notExistingIdentifier}] " .
                                "not presented in external storage"
                            );
                        }
                    }
                }
            );
        } catch (Exception $exception) {
            Log::error(
                "Error occurred during verify existing transactions attempt: {$exception->getMessage()}",
                $exception->getTrace()
            );
        }

        Log::debug('Verify existing transactions import process finished.');
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
                Log::error("Import transaction error occurred: {$e->getMessage()}", $e->getTrace());
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
        Log::debug("Search authorized card with number {$externalTransactionData->card_number_id}");

        /**
         * Card that was authorized.
         *
         * @var Card $authorizedCard
         */
        $authorizedCard = $this->cardEntityService->findWhere([
            Card::CARD_NUMBER => $externalTransactionData->card_number_id,
        ]);

        if (!$authorizedCard) {
            throw new NoCardForTransactionException($externalTransactionData->card_number_id);
        }

        Log::debug("Found authorized card with ID {$authorizedCard->id}");

        /**
         * Validator that authorizes card.
         *
         * @var Validator $validator
         */
        $validator = $this->validatorEntityService->findWhere([
            Validator::EXTERNAL_ID => $externalTransactionData->validators_id,
        ]);

        if (!$validator) {
            throw new NoValidatorForTransactionException($externalTransactionData->validators_id);
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
                $transaction->authorized_at !== $transactionData->authorized_at ||
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
            $payload = [$e->getTrace()];
            if ($e instanceof ValidationException) {
                array_unshift($payload, $e->errors());
            }

            Log::error("Create imported transaction error: {$e->getMessage()}", $payload);
        }

        return null;
    }
}
