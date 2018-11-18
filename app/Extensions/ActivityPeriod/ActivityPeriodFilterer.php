<?php

namespace App\Extensions\ActivityPeriod;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;

/**
 * Query builder helper trait that allows to filter activity period intersection
 */
trait ActivityPeriodFilterer
{
    /**
     * Filter uniqueness of activity period intersection.
     *
     * @see Artifacts/Activity_periods_intersections.xls For periods uniquness explanation
     *
     * @param Builder $builder Builder to add filtering to
     * @param Carbon $activeFrom New activity period start value to check
     * @param Carbon|null $activeTo New activity period end value to check
     *
     * @return Builder
     */
    protected function handleActivityPeriodUniqueness(Builder $builder, Carbon $activeFrom, ?Carbon $activeTo): Builder
    {
        return $builder
            ->where(function (Builder $builder) use ($activeFrom) {
                return $builder
                    ->where('active_from', '<=', $activeFrom)
                    ->where(function (Builder $builder) use ($activeFrom) {
                        return $builder
                            /* Covers 4, 5, 6 intersection cases*/
                            ->orWhere('active_to', '>=', $activeFrom)
                            /* Covers 9, 10 intersection cases*/
                            ->orWhereNull('active_to');
                    });
            })
            ->orWhere(function (Builder $builder) use ($activeFrom, $activeTo) {
                /* Covers 1, 2, 7 intersection cases*/
                return $builder
                    ->where('active_from', '>=', $activeFrom)
                    ->when($activeTo, function (Builder $builder) use ($activeTo) {
                        /* Covers 3, 8 intersection cases*/
                        return $builder
                            ->where('active_from', '<=', $activeTo);
                    });
            });
    }
}
