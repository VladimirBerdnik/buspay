<?php

namespace App\Domain\Import;

use App\Domain\Import\Dto\ExternalTransactionData;
use App\Models\Transaction;
use Carbon\Carbon;
use Log;

/**
 * Imported transactions verifier. Allows to verify imported items in local and external storage.
 */
class TransactionsVerifier extends ExternalEntitiesVerifierService
{
    /**
     * Class name of local items to verify.
     *
     * @var string
     */
    protected $localModelsClass = Transaction::class;

    /**
     * Key name in local storage that is used to link local and remote records.
     *
     * @var string
     */
    protected $localItemsForeignKey = Transaction::EXTERNAL_ID;

    /**
     * Primary key name of entities in external storage.
     *
     * @var string
     */
    protected $foreignItemsKey = ExternalTransactionData::ID;

    /**
     * External items storage name.
     *
     * @var string
     */
    protected $externalStorageName = 'transactions';

    /**
     * Performs items verification in local and external storage.
     *
     * @param Carbon|null $verifyFrom Date from which transactions should be verified
     */
    public function start(?Carbon $verifyFrom): void
    {
        $verifyFrom = $verifyFrom ?? Carbon::today()->subDays(Carbon::DAYS_PER_WEEK);

        Log::debug(
            "Verifying process started. Will verify transactions, authorized >= {$verifyFrom->toDateString()}"
        );

        $verifiedTransactionsFilter = [
            [Transaction::AUTHORIZED_AT, '>=', $verifyFrom],
        ];

        $this->verify($verifiedTransactionsFilter);
    }
}
