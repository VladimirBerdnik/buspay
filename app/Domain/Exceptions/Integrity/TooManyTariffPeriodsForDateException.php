<?php

namespace App\Domain\Exceptions\Integrity;

use App\Models\TariffPeriod;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Thrown when multiple tariff periods for date are exists.
 */
class TooManyTariffPeriodsForDateException extends BusinessLogicIntegrityException
{
    /**
     * Date for which many tariff periods exists.
     *
     * @var Carbon
     */
    protected $date;

    /**
     * List of tariff periods for date.
     *
     * @var Collection|TariffPeriod[]
     */
    protected $tariffPeriods;

    /**
     * Thrown when multiple tariff periods for date are exists.
     *
     * @param Carbon $date Date for which many tariff periods exists
     * @param Collection|TariffPeriod[] $tariffPeriods List of tariff periods for date
     */
    public function __construct(Carbon $date, Collection $tariffPeriods)
    {
        parent::__construct('Few tariff periods for date');
        $this->date = $date;
        $this->tariffPeriods = $tariffPeriods;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $periodsIdentifiers = $this->tariffPeriods->pluck(TariffPeriod::ID)->toArray();
        $date = $this->date->toIso8601String();

        return "For date {$date} few tariff periods exists: " . implode(', ', $periodsIdentifiers);
    }
}
