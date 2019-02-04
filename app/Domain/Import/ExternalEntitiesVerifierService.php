<?php

namespace App\Domain\Import;

use App\Extensions\EntityService;
use App\Extensions\ErrorsReporter;
use Exception;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Log;
use Saritasa\LaravelRepositories\DTO\SortOptions;

/**
 * Imported entities verifier. Allows to verify imported items in local and external storage.
 */
abstract class ExternalEntitiesVerifierService extends ExternalEntitiesImportService
{
    /**
     * Local entities service.
     *
     * @var EntityService
     */
    protected $localEntitiesService;

    /**
     * Class name of local items to verify.
     *
     * @var string
     */
    protected $localModelsClass;

    /**
     * Key name in local storage that is used to link local and remote records.
     *
     * @var string
     */
    protected $localItemsForeignKey;

    /**
     * Primary key name of entities in external storage.
     *
     * @var string
     */
    protected $foreignItemsKey;

    /**
     * Imported entities verifier. Allows to verify imported items in local and external storage.
     *
     * @param ConnectionInterface $connection External storage connection
     * @param ErrorsReporter $errorsReporter Errors and messages reporter
     * @param EntityService $localEntitiesService Local entities service
     */
    public function __construct(
        ConnectionInterface $connection,
        ErrorsReporter $errorsReporter,
        EntityService $localEntitiesService
    ) {
        parent::__construct($connection, $errorsReporter);
        $this->localEntitiesService = $localEntitiesService;
    }

    /**
     * Returns verified entity class short name.
     *
     * @return string
     */
    protected function getEntityName(): string
    {
        return array_last(explode('\\', $this->localModelsClass));
    }

    /**
     * Returns chunk size for collections of items to verify.
     *
     * @return integer
     */
    protected function getChunkSize(): int
    {
        return config('import.verify.verifiedItemsChunkSize') ?? 50;
    }

    /**
     * Verifies existing records presence in external storage.
     *
     * @param mixed[] $filter Filter conditions that should be applied to retrieve list of verified records
     *
     * @return integer Amount of verified records
     */
    protected function verify(array $filter = []): int
    {
        $startTime = time();

        $entityName = $this->getEntityName();

        Log::debug("Verify existing [{$entityName}] records process started.");

        $verifiedItemsCount = 0;

        try {
            $this->localEntitiesService->chunkWith(
                [],
                [],
                $filter,
                new SortOptions($this->localItemsForeignKey),
                $this->getChunkSize(),
                function (Collection $items, int $pageNumber) use ($entityName, &$verifiedItemsCount): void {
                    $verifiedItemsCount += $items->count();

                    Log::debug("{$entityName}s to verify chunk #{$pageNumber} with {$items->count()} items retrieved.");

                    // This list of identifiers are exists in our storage
                    $localIdentifiers = $items->pluck($this->localItemsForeignKey);

                    // Let's retrieve list of items with same identifiers from external storage
                    $externalIdentifiers = $this->getStorage()
                        ->whereIn($this->foreignItemsKey, $localIdentifiers)
                        ->get()
                        ->pluck($this->foreignItemsKey);

                    // Now let's see difference between local and external entities identifiers
                    $notExistingIdentifiers = $localIdentifiers->diff($externalIdentifiers);

                    if ($notExistingIdentifiers->isEmpty()) {
                        return;
                    }

                    foreach ($notExistingIdentifiers as $notExistingIdentifier) {
                        Log::alert(
                            "{$entityName} with external ID [{$notExistingIdentifier}] not exists in external storage"
                        );
                    }
                }
            );
        } catch (Exception $exception) {
            $this->getErrorsReporter()->reportException($exception);

            Log::alert(
                "Error occurred during verify existing [{$entityName}] records attempt: {$exception->getMessage()}"
            );
        }

        $importDuration = time() - $startTime;

        Log::info(
            "{$entityName}s verifying took ~{$importDuration} second(s), verified {$verifiedItemsCount} record(s)"
        );

        return $verifiedItemsCount;
    }
}
