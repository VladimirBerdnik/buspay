<?php

namespace App\Models;

use App\Extensions\ActivityPeriod\IActivityPeriod;
use App\Extensions\ActivityPeriod\IActivityPeriodMaster;
use App\Extensions\ActivityPeriod\IHasActivityPeriodsHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Driver that can drive buses. Works in transport companies.
 *
 * @property int $id Driver unique identifier
 * @property int $company_id Company identifier in which this driver works
 * @property string $full_name Driver full name
 * @property int $bus_id Bus identifier, on which this driver usually works
 * @property int $card_id Current driver card identifier
 * @property bool $active Does this driver works or not, can be assigned to route or not
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Bus $bus Bus on which this driver usually works
 * @property Company $company Company in which this driver works
 * @property Collection|Card[] $cards All cards that this driver have had
 * @property Collection|DriversCard[] $driversCards Information about this driver with cards activity periods
 * @property Card $card Current driver card
 * @property Collection|RouteSheet[] $routeSheets All route sheets where this driver was
 */
class Driver extends Model implements IHasActivityPeriodsHistory, IActivityPeriodMaster
{
    use SoftDeletes;

    public const ID = 'id';
    public const COMPANY_ID = 'company_id';
    public const FULL_NAME = 'full_name';
    public const BUS_ID = 'bus_id';
    public const CARD_ID = 'card_id';
    public const ACTIVE = 'active';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DELETED_AT = 'deleted_at';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'drivers';

    /**
     * The attributes that should be cast to native types.
     *
     * @var string[]
     */
    protected $casts = [
        self::ID => 'int',
        self::COMPANY_ID => 'int',
        self::BUS_ID => 'int',
        self::CARD_ID => 'int',
        self::ACTIVE => 'bool',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var string[]
     */
    protected $dates = [
        self::CREATED_AT,
        self::UPDATED_AT,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        self::COMPANY_ID,
        self::FULL_NAME,
        self::CARD_ID,
        self::BUS_ID,
        self::ACTIVE,
    ];

    /**
     * Bus on which this driver usually works.
     *
     * @return BelongsTo
     */
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Company in which this driver works.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Current driver card.
     *
     * @return BelongsTo
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * All cards that this driver have had.
     *
     * @return BelongsToMany
     */
    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'drivers_cards')
            ->withPivot(DriversCard::ID, DriversCard::ACTIVE_FROM, DriversCard::ACTIVE_TO, DriversCard::DELETED_AT)
            ->withTimestamps();
    }

    /**
     * All route sheets where this driver was.
     *
     * @return HasMany
     */
    public function routeSheets(): HasMany
    {
        return $this->hasMany(RouteSheet::class);
    }

    /**
     * Information about this driver with cards activity periods.
     *
     * @return HasMany
     */
    public function driversCards(): HasMany
    {
        return $this->hasMany(DriversCard::class);
    }

    /**
     * Returns list of activity periods.
     *
     * @return Collection|IActivityPeriod[]
     */
    public function getActivityPeriodsRecords(): Collection
    {
        return $this->driversCards;
    }
}
