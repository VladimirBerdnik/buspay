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
    private $date;

    /**
     * List of tariff periods for date.
     *
     * @var Collection|TariffPeriod[]
     */
    private $tariffPeriods;

    /**
     * Thrown when multiple tariff periods for date are exists.
     *
     * @param Carbon $date Date for which many tariff periods exists
     * @param Collection $tariffPeriods List of tariff periods for date
     */
    public function __construct(Carbon $date, Collection $tariffPeriods)
    {
        parent::__construct('Несколько периодов тарифов на дату');
        $this->date = $date;
        $this->tariffPeriods = $tariffPeriods;
    }

    /**
     * Returns Date for which many tariff periods exists.
     *
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * Returns List of tariff periods for date.
     *
     * @return TariffPeriod[]|Collection
     */
    public function getTariffPeriods()
    {
        return $this->tariffPeriods;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $periodsIdentifiers = $this->getTariffPeriods()->pluck(TariffPeriod::ID)->toArray();
        $date = $this->getDate()->toIso8601String();

        return "На дату {$date} есть несколько периодов тарифов: " .
            implode(', ', $periodsIdentifiers);
    }
}
