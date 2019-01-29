<?php

namespace App\Domain\Import;

use App\Domain\Dto\ReplenishmentData;
use App\Domain\EntitiesServices\CardEntityService;
use App\Domain\EntitiesServices\ReplenishmentEntityService;
use App\Domain\Exceptions\Integrity\NoCardForReplenishmentException;
use App\Domain\Exceptions\Integrity\ReplenishmentMismatchException;
use App\Domain\Exceptions\Integrity\TooManyReplenishmentWithExternalIdException;
use App\Domain\Import\Dto\ExternalReplenishmentData;
use App\Models\Card;
use App\Models\Replenishment;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Throwable;

/**
 * Replenishment importer. Allows to import replenishment records from external storage.
 */
class ReplenishmentImporter extends ExternalEntitiesImportService
{
    /**
     * Replenishment entity service.
     *
     * @var ReplenishmentEntityService
     */
    private $replenishmentService;

    /**
     * Card entities service.
     *
     * @var CardEntityService
     */
    private $cardEntityService;

    /**
     * Format of stored date in external storage.
     *
     * @var string
     */
    private $externalStorageDateFormat = 'YmdHis';

    /**
     * Replenishment importer. Allows to import replenishment records from external storage.
     *
     * @param ConnectionInterface $connection External storage connection
     * @param ReplenishmentEntityService $replenishmentService Replenishment entity service
     * @param CardEntityService $cardEntityService Card entities service
     */
    public function __construct(
        ConnectionInterface $connection,
        ReplenishmentEntityService $replenishmentService,
        CardEntityService $cardEntityService
    ) {
        parent::__construct($connection);
        $this->replenishmentService = $replenishmentService;
        $this->cardEntityService = $cardEntityService;
    }

    /**
     * Returns chunk size for collections of items to import.
     *
     * @return integer
     */
    private function getChunkSize(): int
    {
        return config('import.replenishment.importChunkSize') ?? 50;
    }

    /**
     * Returns days interval within replenishment synchronization should be performed.
     *
     * @return integer
     */
    private function getVerifyingDaysInterval(): int
    {
        return config('import.replenishment.synchronisationDaysInterval') ?? 0;
    }

    /**
     * Returns suspicious replenishment amount.
     *
     * @return integer|null
     */
    private function getSuspiciousAmountLimit(): ?int
    {
        return config('import.replenishment.suspiciousAmountLimit') ?? null;
    }

    /**
     * Performs replenishment import from external replenishment storage.
     *
     * @param Carbon|null $importFrom When passed then starts import from given date
     */
    public function import(?Carbon $importFrom): void
    {
        $startTime = time();

        $importFrom = $importFrom ?? Carbon::today()->subDays($this->getVerifyingDaysInterval());

        $this->verifyReplenishment($importFrom);

        $verifyDuration = time() - $startTime;

        $this->importData($importFrom);

        $importDuration = time() - $startTime - $verifyDuration;

        Log::info(
            "Replenishment verifying took ~{$verifyDuration} second(s), import took ~{$importDuration} second(s)"
        );
    }

    /**
     * Import data from external storage.
     *
     * @param Carbon $importFrom Start import from given date
     */
    private function importData(Carbon $importFrom): void
    {
        Log::debug("Replenishment details import process started");

        $this->getConnection()
            ->table('payments')
            ->where(ExternalReplenishmentData::TXN_DATE, '>=', $importFrom->format($this->externalStorageDateFormat))
            ->orderBy(ExternalReplenishmentData::ID)
            ->chunk($this->getChunkSize(), function (Collection $items, int $pageNumber): void {
                Log::debug("Replenishment chunk #{$pageNumber} retrieved. Chunk size: " . $items->count());
                $this->importChunk($items);
            });

        Log::debug('Updated replenishment details import process finished.');
    }

    /**
     * Verifies existing replenishment that they are exists in external storage.
     *
     * @param Carbon $verifyFrom Date from which need to verify replenishment records
     */
    private function verifyReplenishment(Carbon $verifyFrom): void
    {
        Log::debug('Verify existing replenishment import process started.');

        try {
            $verifyTo = Carbon::today();
            $where = [
                [Replenishment::REPLENISHED_AT, '>=', $verifyFrom->format($this->externalStorageDateFormat)],
                [Replenishment::REPLENISHED_AT, '<=', $verifyTo->format($this->externalStorageDateFormat)],
            ];
            $this->replenishmentService->chunkWith(
                [],
                [],
                $where,
                new SortOptions(Replenishment::REPLENISHED_AT),
                $this->getChunkSize(),
                function (Collection $items, int $pageNumber) use (&$localStorageReplenishmentIdentifiers): void {
                    Log::debug(
                        "Replenishment to verify chunk #{$pageNumber} retrieved. Chunk size: " . $items->count()
                    );

                    // This list of identifiers are exists in our storage
                    $localStorageReplenishmentIdentifiers = $items->pluck(Replenishment::EXTERNAL_ID);

                    // Let's retrieve list ov replenishment with same identifiers from external storage
                    $externalStorageReplenishmentIdentifiers = $this->getConnection()
                        ->table('payments')
                        ->whereIn(ExternalReplenishmentData::ID, $localStorageReplenishmentIdentifiers)
                        ->get()
                        ->pluck(ExternalReplenishmentData::ID);

                    // Now let's see difference between local and external replenishment
                    $notExistingIdentifiers = $localStorageReplenishmentIdentifiers
                        ->diff($externalStorageReplenishmentIdentifiers);

                    if ($notExistingIdentifiers->isNotEmpty()) {
                        foreach ($notExistingIdentifiers as $notExistingIdentifier) {
                            Log::error(
                                "Replenishment with external identifier [{$notExistingIdentifier}] " .
                                "not presented in external storage"
                            );
                        }
                    }
                }
            );
        } catch (Exception $exception) {
            Log::error(
                "Error occurred during verify existing replenishment attempt: {$exception->getMessage()}",
                $exception->getTrace()
            );
        }

        Log::debug('Verify existing replenishment import process finished.');
    }

    /**
     * Performs import of chunk of external replenishment details.
     *
     * @param Collection|object[] $items Collection of items to import
     *
     * @throws Throwable
     */
    private function importChunk(Collection $items): void
    {
        foreach ($items as $index => $item) {
            try {
                Log::debug("Prepare import replenishment #{$index} with details " . json_encode($item));

                $externalReplenishmentData = new ExternalReplenishmentData((array)$item);

                $replenishment = $this->importExternalReplenishment($externalReplenishmentData);

                if ($replenishment) {
                    Log::debug(
                        "Replenishment with external id [{$replenishment->external_id}] " .
                        "synchronized as [ID={$replenishment->id}]"
                    );
                } else {
                    Log::debug("Replenishment with external id [{$externalReplenishmentData->id}] wasn't synchronized");
                }
            } catch (Exception $e) {
                Log::error("Import replenishment error occurred: {$e->getMessage()}", $e->getTrace());
            }
        }
    }

    /**
     * Performs external replenishment data import.
     *
     * @param ExternalReplenishmentData $externalReplenishmentData Details of external replenishment to import
     *
     * @return Replenishment|null
     *
     * @throws Throwable
     */
    private function importExternalReplenishment(ExternalReplenishmentData $externalReplenishmentData): ?Replenishment
    {
        Log::debug("Search replenished card with number {$externalReplenishmentData->account}");

        /**
         * Card that was replenished.
         *
         * @var Card $replenishedCard
         */
        $replenishedCard = $this->cardEntityService->findWhere([
            Card::CARD_NUMBER => $externalReplenishmentData->account,
        ]);

        if (!$replenishedCard) {
            throw new NoCardForReplenishmentException($externalReplenishmentData->account);
        }

        Log::debug("Found replenished card with ID {$replenishedCard->id}");

        if ($externalReplenishmentData->sum <= 0) {
            Log::notice(
                "Negative or zero payment amount [{$externalReplenishmentData->sum}] detected " .
                "in external payment [{$externalReplenishmentData->id}]. Still trying to import"
            );
        }

        $suspiciousAmountLimit = $this->getSuspiciousAmountLimit();
        if ($suspiciousAmountLimit && $externalReplenishmentData->sum > $suspiciousAmountLimit) {
            Log::notice(
                "Big payment amount [{$externalReplenishmentData->sum}] detected " .
                "in external payment [{$externalReplenishmentData->id}]. Still trying to import"
            );
        }

        if (!$replenishedCard->active) {
            Log::notice("Card [{$replenishedCard->id}] disabled. Still trying to import");
        }

        $replenishmentData = new ReplenishmentData([
            ReplenishmentData::EXTERNAL_ID => $externalReplenishmentData->id,
            ReplenishmentData::AMOUNT => $externalReplenishmentData->sum,
            ReplenishmentData::CARD_ID => $replenishedCard->id,
            ReplenishmentData::REPLENISHED_AT => Carbon::parse($externalReplenishmentData->txn_date),
        ]);

        Log::debug("Search replenishment with ID {$replenishmentData->external_id} to check");

        $matchedItems = $this->replenishmentService->getWhere([
            Replenishment::EXTERNAL_ID => $replenishmentData->external_id,
        ]);

        if ($matchedItems->count() > 1) {
            throw new TooManyReplenishmentWithExternalIdException($replenishmentData->external_id);
        }

        if ($matchedItems->count() === 1) {
            /**
             * Found replenishment to check.
             *
             * @var Replenishment $replenishment
             */
            $replenishment = $matchedItems->first();

            Log::debug("Replenishment [{$replenishment->id}] found");

            if ($replenishment->card_id !== $replenishmentData->card_id ||
                $replenishment->amount !== $replenishmentData->amount ||
                $replenishment->replenished_at !== $replenishmentData->replenished_at
            ) {
                throw new ReplenishmentMismatchException($replenishmentData->external_id);
            }
        } else {
            Log::debug('No existing replenishment found to update. Trying to create');

            $replenishment = $this->createReplenishment($replenishmentData);
        }

        return $replenishment;
    }

    /**
     * Creates new replenishment with imported parameters.
     *
     * @param ReplenishmentData $replenishmentData Replenishment details to create new replenishment
     *
     * @return Replenishment|null
     *
     * @throws Throwable
     */
    private function createReplenishment(ReplenishmentData $replenishmentData): ?Replenishment
    {
        try {
            return $this->replenishmentService->store($replenishmentData);
        } catch (Exception $e) {
            $payload = [$e->getTrace()];
            if ($e instanceof ValidationException) {
                array_unshift($payload, $e->errors());
            }

            Log::error("Create imported replenishment error: {$e->getMessage()}", $payload);
        }

        return null;
    }
}
