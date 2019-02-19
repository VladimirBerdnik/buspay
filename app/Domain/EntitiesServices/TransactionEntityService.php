<?php

namespace App\Domain\EntitiesServices;

use App\Domain\Dto\TransactionData;
use App\Extensions\EntityService;
use App\Models\Card;
use App\Models\RouteSheet;
use App\Models\Tariff;
use App\Models\Transaction;
use App\Models\Validator;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Throwable;
use Validator as DataValidator;

/**
 * Transaction entity service.
 */
class TransactionEntityService extends EntityService
{
    /**
     * Returns validation rule to store transaction.
     *
     * @return string[]|GenericRuleSet[]
     */
    protected function getTransactionValidationRules(): array
    {
        return [
            Transaction::CARD_ID => Rule::required()->exists('cards', Card::ID),
            Transaction::VALIDATOR_ID => Rule::required()->exists('validators', Validator::ID),
            Transaction::TARIFF_ID => Rule::present()->nullable()->exists('tariffs', Tariff::ID),
            Transaction::ROUTE_SHEET_ID => Rule::present()->nullable()->exists('route_sheets', RouteSheet::ID),
            Transaction::EXTERNAL_ID => Rule::required()
                // Transaction should have unique external identifier to avoid double storing attempt
                ->unique('transactions', Transaction::EXTERNAL_ID),
            Transaction::AMOUNT => Rule::present()->nullable()->min(0),
            Transaction::AUTHORIZED_AT => Rule::required()->date()->beforeOrEqual(Carbon::today()->endOfDay()),
        ];
    }

    /**
     * Creates transaction details.
     *
     * @param TransactionData $transactionData New transaction details
     *
     * @return Transaction
     *
     * @throws RepositoryException
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(TransactionData $transactionData): Transaction
    {
        Log::debug("Create transaction with external ID [{$transactionData->external_id}] attempt");

        $transaction = new Transaction($transactionData->toArray());

        DataValidator::validate($transactionData->toArray(), $this->getTransactionValidationRules());

        $this->getRepository()->save($transaction);

        Log::debug("Transaction [{$transaction->id}] created");

        return $transaction;
    }

    /**
     * Links transaction with route sheet.
     *
     * @param Transaction $transaction Transaction to link
     * @param RouteSheet|null $routeSheet Route sheet to link with
     *
     * @throws RepositoryException
     */
    public function assignRouteSheet(Transaction $transaction, ?RouteSheet $routeSheet): void
    {
        $routeSheetId = $routeSheet ? $routeSheet->id : null;

        Log::debug("Link transaction [{$transaction->id}] with route sheet [{$routeSheetId}]");

        $transaction->route_sheet_id = $routeSheetId;

        $this->getRepository()->save($transaction);
    }
}
