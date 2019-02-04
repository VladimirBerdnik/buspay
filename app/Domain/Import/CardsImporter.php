<?php

namespace App\Domain\Import;

use App\Domain\Dto\CardData;
use App\Domain\EntitiesServices\CardEntityService;
use App\Domain\Import\Dto\ExternalCardData;
use App\Domain\Import\Exceptions\Integrity\TooManyCardsWithNumberException;
use App\Extensions\ErrorsReporter;
use App\Models\Card;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\Exceptions\NotImplementedException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Enums\OrderDirections;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Throwable;

/**
 * Cards importer. Allows to import cards records from external storage.
 */
class CardsImporter extends ExternalEntitiesImportService
{
    /**
     * Card entity service.
     *
     * @var CardEntityService
     */
    private $cardService;

    /**
     * Cards importer. Allows to import cards records from external storage.
     *
     * @param ConnectionInterface $connection External storage connection
     * @param ErrorsReporter $errorsReporter Errors and messages reporter
     * @param CardEntityService $cardService Card entity service
     */
    public function __construct(
        ConnectionInterface $connection,
        ErrorsReporter $errorsReporter,
        CardEntityService $cardService
    ) {
        parent::__construct($connection, $errorsReporter);
        $this->cardService = $cardService;
    }

    /**
     * Returns chunk size for collections of items to import.
     *
     * @return integer
     */
    private function getChunkSize(): int
    {
        return config('import.card.importChunkSize') ?? 50;
    }

    /**
     * Performs cards import from external cards storage.
     *
     * @param Carbon|null $importDate Import data, updated after passed date
     *
     * @throws InvalidEnumValueException
     * @throws NotImplementedException
     */
    public function import(?Carbon $importDate = null): void
    {
        $startTime = time();

        /**
         * Remember date for synchronization before unsyncronized cards import as in this case we can loose some
         * records resynchronized records will change max synchronization date.
         */
        $dateForSynchronization = $importDate ?? $this->getDateForSynchronization();

        $this->reimportUnsynchronized();

        $reimportDuration = time() - $startTime;

        $this->importUpdatedData($dateForSynchronization);

        $importDuration = time() - $startTime - $reimportDuration;

        Log::info("Cards reimport took ~{$reimportDuration} second(s). Cards import took ~{$importDuration} second(s)");
    }

    /**
     * Returns date of changes that should be requested from external storage.
     *
     * @return Carbon
     *
     * @throws InvalidEnumValueException
     * @throws NotImplementedException
     */
    private function getDateForSynchronization(): Carbon
    {
        $from = Carbon::now()->setDate(2017, 1, 1)->startOfDay();

        /**
         * Get sorted by synchronization date card records by chunks with size of one element, take date from first item
         * and stop chunking.
         */
        $this->cardService->chunkWith(
            [],
            [],
            [[Card::SYNCHRONIZED_AT, '!=', null]],
            new SortOptions(Card::SYNCHRONIZED_AT, OrderDirections::DESC),
            1,
            function (Collection $items) use (&$from) {
                /**
                 * Last synchronized card.
                 *
                 * @var Card $lastSynchronizedItem
                 */
                $lastSynchronizedItem = $items->first();

                $from = $lastSynchronizedItem->synchronized_at;

                Log::debug("Last synchronization date is {$from->toIso8601String()}");

                return false;
            }
        );

        Log::debug("Date for synchronization is {$from->toIso8601String()}");

        return $from;
    }

    /**
     * Import new data from external storage starting from passed date.
     *
     * @param Carbon $from Date from with need to retrieve fresh data
     */
    private function importUpdatedData(Carbon $from): void
    {
        Log::debug("Updated card details import process started. Retrieve data after {$from->toIso8601String()}");

        $this->getConnection()
            ->table('cards')
            ->where(ExternalCardData::UPDATED_AT, '>=', $from)
            ->orderBy(ExternalCardData::UPDATED_AT)
            ->chunk($this->getChunkSize(), function (Collection $items, int $pageNumber): void {
                Log::debug("Cards chunk #{$pageNumber} retrieved. Chunk size: " . $items->count());

                $this->importChunk($items);
            });

        Log::debug('Updated card details import process finished.');
    }

    /**
     * Performs attempt to reimport card details with errors during previous import.
     *
     * @throws NotImplementedException
     * @throws InvalidEnumValueException
     */
    private function reimportUnsynchronized(): void
    {
        Log::debug('Not synchronized cards import process started.');

        $this->cardService->chunkWith(
            [],
            [],
            [Card::SYNCHRONIZED_AT => null],
            new SortOptions(Card::ID),
            $this->getChunkSize(),
            function (Collection $notSynchronizedCards): void {
                $cardNumbers = $notSynchronizedCards->pluck(Card::CARD_NUMBER)->toArray();

                Log::debug('Request external cards details by card number to synchronize', $cardNumbers);

                $this->getConnection()
                    ->table('cards')
                    ->whereIn(ExternalCardData::CARD_NUMBER, $cardNumbers)
                    ->orderBy(ExternalCardData::UPDATED_AT)
                    ->chunk($this->getChunkSize(), function (Collection $items, int $pageNumber): void {
                        Log::debug("Cards chunk #{$pageNumber} retrieved. Chunk size: " . $items->count());

                        $this->importChunk($items);
                    });
            }
        );

        Log::debug('Not synchronized cards import process finished.');
    }

    /**
     * Performs import of chunk of external card details.
     *
     * @param Collection|object[] $items Collection of items to import
     *
     * @throws Throwable
     */
    private function importChunk(Collection $items): void
    {
        foreach ($items as $index => $item) {
            try {
                Log::debug("Prepare import card #{$index} with details " . json_encode($item));

                $externalCardData = new ExternalCardData((array)$item);

                $card = $this->importExternalCard($externalCardData);

                Log::debug(
                    "Card with number [{$externalCardData->card_number}] synchronized as [ID={$card->id}]"
                );
            } catch (Exception $e) {
                $this->getErrorsReporter()->reportException($e, (array)$item);

                Log::error("Import card error occurred: {$e->getMessage()}");
            }
        }
    }

    /**
     * Performs external card data import.
     *
     * @param ExternalCardData $externalCardData Details of external card to import
     *
     * @return Card
     *
     * @throws RepositoryException
     * @throws Throwable
     */
    private function importExternalCard(ExternalCardData $externalCardData): Card
    {
        $cardData = new CardData([
            CardData::CARD_NUMBER => $externalCardData->card_number,
            CardData::UIN => $externalCardData->uin,
            CardData::ACTIVE => $externalCardData->enable,
            CardData::CARD_TYPE_ID => $externalCardData->card_type,
            CardData::SYNCHRONIZED_AT => Carbon::instance(new DateTime($externalCardData->updated_at)),
        ]);

        Log::debug("Search card with number {$cardData->card_number} for update");

        $matchedItems = $this->cardService
            ->getWhere([Card::CARD_NUMBER => $cardData->card_number]);

        if ($matchedItems->count() > 1) {
            throw new TooManyCardsWithNumberException($cardData->card_number);
        } elseif ($matchedItems->count() === 1) {
            /**
             * Found card to update.
             *
             * @var Card $card
             */
            $card = $matchedItems->first();
            Log::debug("Card [{$card->id}] found to update");

            $card = $this->updateCard($card, $cardData);
        } else {
            Log::debug('No existing card found to update. Trying to create');

            $card = $this->createCard($cardData);
        }

        return $card;
    }

    /**
     * Creates new card with imported parameters.
     *
     * @param CardData $cardData Card details to create new card
     *
     * @return Card
     *
     * @throws RepositoryException
     * @throws Throwable
     */
    private function createCard(CardData $cardData): Card
    {
        try {
            $card = $this->cardService->store($cardData);
        } catch (Exception $e) {
            $this->getErrorsReporter()->reportException($e, $cardData->toArray());

            $payload = [];
            if ($e instanceof ValidationException) {
                $payload = $e->errors();
            }

            Log::error("Create imported card error: {$e->getMessage()}", $payload);

            Log::notice("Trying to create fallback card {$cardData->card_number} record");

            $card = $this->cardService->fallbackCreate($cardData->card_number);
        }

        return $card;
    }

    /**
     * Updates card with newly imported details.
     *
     * @param Card $card Card to update
     * @param CardData $cardData New card details to update
     *
     * @return Card
     *
     * @throws RepositoryException
     * @throws Throwable
     */
    private function updateCard(Card $card, CardData $cardData): Card
    {
        try {
            $this->cardService->update($card, $cardData);
        } catch (Exception $e) {
            $this->getErrorsReporter()->reportException($e, $cardData->toArray());

            $payload = [];
            if ($e instanceof ValidationException) {
                $payload = $e->errors();
            }

            Log::error("Update imported card error: {$e->getMessage()}", $payload);

            Log::notice("Trying to update fallback card {$cardData->card_number} record");

            $card = $this->cardService->fallbackUpdate($card);
        }

        return $card;
    }
}
