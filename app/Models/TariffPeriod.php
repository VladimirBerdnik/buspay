<?php

namespace App\Models;

use App\Extensions\ActivityPeriod\HasActivityPeriod;
use App\Extensions\ActivityPeriod\IHasActivityPeriod;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Tariffs activity periods.
 *
 * @property int $id Tariff period unique identifier
 * @property Carbon $active_from Start date of activity period of this record
 * @property Carbon $active_to End date of activity period of this record
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Collection|TariffFare[] $tariffFares Tariff fares that are applicable during this period
 */
class TariffPeriod extends Model implements IHasActivityPeriod
{
    use SoftDeletes;
    use HasActivityPeriod;

    public const ID = 'id';
    public const ACTIVE_FROM = 'active_from';
    public const ACTIVE_TO = 'active_to';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DELETED_AT = 'deleted_at';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tariff_periods';

    /**
     * The attributes that should be cast to native types.
     *
     * @var string[]
     */
    protected $casts = [
        self::ID => 'int',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var string[]
     */
    protected $dates = [
        self::ACTIVE_FROM,
        self::ACTIVE_TO,
        self::CREATED_AT,
        self::UPDATED_AT,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $fillable = [
        self::ACTIVE_FROM,
        self::ACTIVE_TO,
    ];

    /**
     * Tariff fares that are applicable during this period.
     *
     * @return HasMany
     */
    public function tariffFares(): HasMany
    {
        return $this->hasMany(TariffFare::class);
    }
    /**
     * Returns list of attributes involved into activity period. Each of them should be used only once at any moment.
     *
     * @return string[]
     */
    public function getUniquenessAttributes(): array
    {
        return [];
    }
}
