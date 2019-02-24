<?php

namespace App\Domain\Export;

use App\Domain\Dto\Filters\TransactionsFilterData;
use App\Domain\Enums\CardTypesIdentifiers;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Collection;
use Log;
use Saritasa\LaravelRepositories\DTO\SortOptions;

/**
 * Transactions records exporter.
 */
class TransactionsExporter extends CsvFileExporter
{
    /**
     * Transactions records storage.
     *
     * @var TransactionRepository
     */
    private $repository;

    /**
     * Transactions records exporter.
     *
     * @param TransactionRepository $repository Transactions records storage
     */
    public function __construct(TransactionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Exports transactions records into file with respect to requested filter and sorting details.
     *
     * @param TransactionsFilterData $transactionsFilter Filter criteria that should be applied against list of
     *     transactions
     * @param SortOptions $sortOptions Requested sort options of transactions list
     * @param bool $onlyDriverCardsNumbersVisible Should be card numbers of drivers cards visible only or not
     *
     * @return string
     */
    public function export(
        TransactionsFilterData $transactionsFilter,
        SortOptions $sortOptions,
        bool $onlyDriverCardsNumbersVisible
    ): string {
        Log::debug('Transactions export process started', $transactionsFilter->toArray());

        $filename = $this->getTempFileName();
        $file = $this->openFile($filename);

        $headers = [
            trans('transactionsExport.id'),
            trans('transactionsExport.card.card_number'),
            trans('transactionsExport.card.cardType.name'),
            trans('transactionsExport.tariff.name'),
            trans('transactionsExport.amount'),
            trans('transactionsExport.routeSheet.bus.state_number'),
            trans('transactionsExport.routeSheet.route.name'),
            trans('transactionsExport.validator.serial_number'),
            trans('transactionsExport.routeSheet.company.name'),
            trans('transactionsExport.authorized_at'),
        ];
        $this->putRow($file, $headers);

        $this->repository->getFilteredChunkWith(
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
            $sortOptions,
            $this->exportChunkSize,
            function (Collection $transactions) use ($onlyDriverCardsNumbersVisible, $file): void {
                /**
                 * Transaction to put into file.
                 *
                 * @var Transaction $transaction
                 */
                foreach ($transactions as $transaction) {
                    $cardNumberMasked = $onlyDriverCardsNumbersVisible
                        && $transaction->card->card_type_id !== CardTypesIdentifiers::DRIVER;
                    $requiredData = [
                        $transaction->id,
                        $cardNumberMasked ? '********' : $transaction->card->card_number,
                        $transaction->card->cardType->name,
                        $transaction->tariff ? $transaction->tariff->name : null,
                        $transaction->amount,
                        $transaction->routeSheet ? $transaction->routeSheet->bus->state_number : null,
                        $transaction->routeSheet && $transaction->routeSheet->route
                            ? $transaction->routeSheet->route->name
                            : null,
                        $transaction->validator->serial_number,
                        $transaction->routeSheet ? $transaction->routeSheet->company->name : null,
                        $transaction->authorized_at->toIso8601String(),
                    ];
                    $this->putRow($file, $requiredData);
                }
            }
        );

        $this->closeFile($file);

        Log::debug("Transactions export process to file {$filename} finished");

        return $filename;
    }
}
