<?php

namespace App\Models;

use App\Extensions\ActivityPeriod\HasActivityPeriod;
use App\Extensions\ActivityPeriod\IHasActivityPeriod;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Payment tariffs activity period information.
 *
 * @property int $id Tariff unique identifier
 * @property Carbon $active_from Start date of activity period of this record
 * @property Carbon $active_to End date of activity period of this record
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Collection|TariffFare[] $tariffFares All fares of card types on this tariff
 */
class Tariff extends Model implements IHasActivityPeriod
{
    use HasActivityPeriod;
    use SoftDeletes;

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
    protected $table = 'tariffs';

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
     * @var string[]
     */
    protected $fillable = [
        self::ACTIVE_FROM,
        self::ACTIVE_TO,
    ];

    /**
     * All fares of card types on this tariff.
     *
     * @return HasMany
     */
    public function tariffFares(): HasMany
    {
        return $this->hasMany(TariffFare::class);
    }
}
