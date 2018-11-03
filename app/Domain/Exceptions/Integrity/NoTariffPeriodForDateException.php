<?php

namespace App\Domain\Exceptions\Integrity;

use Carbon\Carbon;

/**
 * Thrown when no tariff period for date exist.
 */
class NoTariffPeriodForDateException extends BusinessLogicIntegrityException
{
    /**
     * Date for which tariff period missed.
     *
     * @var Carbon
     */
    private $date;

    /**
     * Thrown when no tariff period for date exist.
     *
     * @param Carbon $date Date for which tariff period missed
     */
    public function __construct(Carbon $date)
    {
        parent::__construct("Нет периода тарифа на дату");
        $this->date = $date;
    }

    /**
     * Returns Date for which tariff period missed.
     *
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        return "На дату {$this->getDate()->toIso8601String()} нет периода тарифа";
    }
}
