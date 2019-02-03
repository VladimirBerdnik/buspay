<?php

namespace App\Domain\Import;

use App\Domain\Dto\ValidatorData;
use App\Domain\EntitiesServices\ValidatorEntityService;
use App\Domain\Import\Dto\ExternalValidatorData;
use App\Domain\Import\Exceptions\Integrity\TooManyValidatorsWithExternalIdException;
use App\Models\Validator;
use Exception;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Throwable;

/**
 * Validators importer. Allows to import validators records from external storage.
 */
class ValidatorsImporter extends ExternalEntitiesImportService
{
    /**
     * Validator entity service.
     *
     * @var ValidatorEntityService
     */
    private $validatorService;

    /**
     * Validators importer. Allows to import validators records from external storage.
     *
     * @param ConnectionInterface $connection External storage connection
     * @param ValidatorEntityService $validatorService Validator entity service
     */
    public function __construct(ConnectionInterface $connection, ValidatorEntityService $validatorService)
    {
        parent::__construct($connection);
        $this->validatorService = $validatorService;
    }

    /**
     * Returns chunk size for collections of items to import.
     *
     * @return integer
     */
    private function getChunkSize(): int
    {
        return config('import.validator.importChunkSize') ?? 50;
    }

    /**
     * Performs validators import from external validators storage.
     *
     * @throws Throwable
     */
    public function import(): void
    {
        $startTime = time();

        $this->verifyValidators();

        $verifyDuration = time() - $startTime;

        $this->importData();

        $importDuration = time() - $startTime - $verifyDuration;

        Log::info("Validators verifying took ~{$verifyDuration} second(s), import took ~{$importDuration} second(s)");
    }

    /**
     * Import data from external storage.
     */
    private function importData(): void
    {
        Log::debug("Validator details import process started");

        $this->getConnection()
            ->table('validators')
            ->orderBy(ExternalValidatorData::ID)
            ->chunk($this->getChunkSize(), function (Collection $items, int $pageNumber): void {
                Log::debug("Validators chunk #{$pageNumber} retrieved. Chunk size: " . $items->count());
                $this->importChunk($items);
            });

        Log::debug('Updated validator details import process finished.');
    }

    /**
     * Verifies existing validators that they are exists in external storage.
     */
    private function verifyValidators(): void
    {
        Log::debug('Verify existing validator import process started.');

        try {
            $this->validatorService->chunkWith(
                [],
                [],
                [],
                new SortOptions(Validator::EXTERNAL_ID),
                $this->getChunkSize(),
                function (Collection $items, int $pageNumber) use (&$localStorageValidatorsIdentifiers): void {
                    Log::debug("Validators to verify chunk #{$pageNumber} retrieved. Chunk size: " . $items->count());

                    // This list of identifiers are exists in our storage
                    $localStorageValidatorsIdentifiers = $items->pluck(Validator::EXTERNAL_ID);

                    // Let's retrieve list ov validators with same identifiers from external storage
                    $externalStorageValidatorsIdentifiers = $this->getConnection()
                        ->table('validators')
                        ->whereIn(ExternalValidatorData::ID, $localStorageValidatorsIdentifiers)
                        ->get()
                        ->pluck(ExternalValidatorData::ID);

                    // Now let's see difference between local and external validators
                    $notExistingIdentifiers = $localStorageValidatorsIdentifiers
                        ->diff($externalStorageValidatorsIdentifiers);

                    if ($notExistingIdentifiers->isNotEmpty()) {
                        foreach ($notExistingIdentifiers as $notExistingIdentifier) {
                            Log::error(
                                "Validator with external identifier [{$notExistingIdentifier}] " .
                                "not presented in external storage"
                            );
                        }
                    }
                }
            );
        } catch (Exception $exception) {
            Log::error(
                "Error occurred during verify existing validators attempt: {$exception->getMessage()}",
                $exception->getTrace()
            );
        }

        Log::debug('Verify existing validators import process finished.');
    }

    /**
     * Performs import of chunk of external validator details.
     *
     * @param Collection|object[] $items Collection of items to import
     *
     * @throws Throwable
     */
    private function importChunk(Collection $items): void
    {
        foreach ($items as $index => $item) {
            try {
                Log::debug("Prepare import validator #{$index} with details " . json_encode($item));

                $externalValidatorData = new ExternalValidatorData((array)$item);

                $validator = $this->importExternalValidator($externalValidatorData);

                if ($validator) {
                    Log::debug(
                        "Validator with external id [{$validator->external_id}] synchronized as [ID={$validator->id}]"
                    );
                } else {
                    Log::debug("Validator with external id [{$externalValidatorData->id}] wasn't synchronized");
                }
            } catch (Exception $e) {
                Log::error("Import validator error occurred: {$e->getMessage()}", $e->getTrace());
            }
        }
    }

    /**
     * Performs external validator data import.
     *
     * @param ExternalValidatorData $externalValidatorData Details of external validator to import
     *
     * @return Validator|null
     *
     * @throws RepositoryException
     * @throws Throwable
     */
    private function importExternalValidator(ExternalValidatorData $externalValidatorData): ?Validator
    {
        $validatorData = new ValidatorData([
            ValidatorData::EXTERNAL_ID => $externalValidatorData->id,
            ValidatorData::SERIAL_NUMBER => $externalValidatorData->serial,
            ValidatorData::MODEL => $externalValidatorData->model,
        ]);

        Log::debug("Search validator with ID {$validatorData->external_id} for update");

        $matchedItems = $this->validatorService
            ->getWhere([Validator::EXTERNAL_ID => $validatorData->external_id]);

        if ($matchedItems->count() > 1) {
            throw new TooManyValidatorsWithExternalIdException($validatorData->external_id);
        } elseif ($matchedItems->count() === 1) {
            /**
             * Found validator to update.
             *
             * @var Validator $validator
             */
            $validator = $matchedItems->first();
            Log::debug("Validator [{$validator->id}] found to update");

            $validator = $this->updateValidator($validator, $validatorData);
        } else {
            Log::debug('No existing validator found to update. Trying to create');

            $validator = $this->createValidator($validatorData);
        }

        return $validator;
    }

    /**
     * Creates new validator with imported parameters.
     *
     * @param ValidatorData $validatorData Validator details to create new validator
     *
     * @return Validator|null
     *
     * @throws Throwable
     */
    private function createValidator(ValidatorData $validatorData): ?Validator
    {
        try {
            return $this->validatorService->store($validatorData);
        } catch (Exception $e) {
            $payload = [$e->getTrace()];
            if ($e instanceof ValidationException) {
                array_unshift($payload, $e->errors());
            }

            Log::error("Create imported validator error: {$e->getMessage()}", $payload);
        }

        return null;
    }

    /**
     * Updates validator with newly imported details.
     *
     * @param Validator $validator Validator to update
     * @param ValidatorData $validatorData New validator details to update
     *
     * @return Validator
     *
     * @throws Throwable
     */
    private function updateValidator(Validator $validator, ValidatorData $validatorData): Validator
    {
        try {
            $this->validatorService->update($validator, $validatorData);
        } catch (Exception $e) {
            $payload = [$e->getTrace()];
            if ($e instanceof ValidationException) {
                array_unshift($payload, $e->errors());
            }

            Log::error("Update imported validator error: {$e->getMessage()}", $payload);
        }

        return $validator;
    }
}
