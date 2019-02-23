<?php

namespace App\Console\Commands;

use App\Domain\EntitiesServices\TransactionEntityService;
use App\Domain\Services\CardAuthorizationService;
use App\Extensions\ErrorsReporter;
use App\Models\Transaction;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Log;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\Exceptions\NotImplementedException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Throwable;

/**
 * Command to process all unassigned transactions.
 */
class ProcessUnassignedTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avtotoleu:process_transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to process all unassigned transactions';

    /**
     * Transaction entity service.
     *
     * @var TransactionEntityService
     */
    private $transactionEntityService;

    /**
     * Service that can process card authorization.
     *
     * @var CardAuthorizationService
     */
    private $cardAuthorizationService;

    /**
     * Reports about exceptions and logged messages.
     *
     * @var ErrorsReporter
     */
    private $errorsReporter;

    /**
     * Create a new command instance.
     *
     * @param TransactionEntityService $transactionEntityService Transaction entity service
     * @param CardAuthorizationService $cardAuthorizationService Service that can process card authorization
     * @param ErrorsReporter $errorsReporter Reports about exceptions and logged messages
     */
    public function __construct(
        TransactionEntityService $transactionEntityService,
        CardAuthorizationService $cardAuthorizationService,
        ErrorsReporter $errorsReporter
    ) {
        parent::__construct();
        $this->transactionEntityService = $transactionEntityService;
        $this->cardAuthorizationService = $cardAuthorizationService;
        $this->errorsReporter = $errorsReporter;
    }

    /**
     * Execute the console command.
     *
     * @return void
     *
     * @throws InvalidEnumValueException
     * @throws NotImplementedException
     */
    public function handle(): void
    {
        Log::debug('Unassigned to route sheets transactions process started');

        $count = 0;

        $this->transactionEntityService->chunkWith(
            [],
            [],
            [[Transaction::ROUTE_SHEET_ID, '=', null]],
            new SortOptions(Transaction::AUTHORIZED_AT),
            50,
            function (Collection $transactions) use (&$count): void {
                foreach ($transactions as $transaction) {
                    try {
                        DB::transaction(function () use ($transaction): void {
                            $this->cardAuthorizationService->processCardAuthorization($transaction);
                        });
                        $count++;
                    } catch (Throwable $exception) {
                        $this->errorsReporter->reportException($exception);
                    }
                }
            }
        );

        Log::debug("Unassigned to route sheets transactions process finished. Processed {$count} item(s)");
    }
}
