<?php

namespace App\Domain\Import;

use App\Domain\Import\Dto\ExternalReplenishmentData;
use App\Models\Replenishment;
use Carbon\Carbon;
use Log;

/**
 * Imported replenishments verifier. Allows to verify imported items in local and external storage.
 */
class ReplenishmentsVerifier extends ExternalEntitiesVerifierService
{
    /**
     * Class name of local items to verify.
     *
     * @var string
     */
    protected $localModelsClass = Replenishment::class;

    /**
     * Key name in local storage that is used to link local and remote records.
     *
     * @var string
     */
    protected $localItemsForeignKey = Replenishment::EXTERNAL_ID;

    /**
     * Primary key name of entities in external storage.
     *
     * @var string
     */
    protected $foreignItemsKey = ExternalReplenishmentData::ID;

    /**
     * Performs items verification in local and external storage.
     *
     * @param Carbon|null $verifyFrom Date from which replenishments should be verified
     */
    public function start(?Carbon $verifyFrom): void
    {
        $verifyFrom = $verifyFrom ?? Carbon::today()->subDays(Carbon::DAYS_PER_WEEK);

        Log::debug(
            "Verifying process started. Will verify replenishments, replenished >= {$verifyFrom->toDateString()}"
        );

        $verifiedReplenishmentsFilter = [
            [Replenishment::REPLENISHED_AT, '>=', $verifyFrom],
        ];

        $this->verify($verifiedReplenishmentsFilter);
    }
}
