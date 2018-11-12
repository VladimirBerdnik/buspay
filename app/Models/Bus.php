<?php

namespace App\Models;

use App\Extensions\ActivityPeriod\IActivityPeriod;
use App\Extensions\ActivityPeriod\IActivityPeriodRelated;
use App\Extensions\ActivityPeriod\IHasActivityPeriodsHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Buses - assets of transport companies. Can serve route.
 *
 * @property int $id Bus unique identifier
 * @property int $company_id Company identifier, to which this bus belongs
 * @property string $model_name Name of bus model
 * @property string $state_number Bus state number
 * @property int $route_id Usual route identifier, on which this bus is
 * @property bool $active Does this bus works or not, can be assigned to route or not
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Company $company Company to which bus belongs
 * @property Route $route Default route that this bus serves
 * @property Collection|Validator[] $validators Installed in this bus validators
 * @property Collection|BusesValidator[] $busesValidators Information about validators in this bus activity periods
 * @property Collection|Driver[] $drivers Drivers that usually work on this bus
 * @property Collection|RouteSheet[] $routeSheets Route sheets with information when, on which route which driver serves
 */
class Bus extends Model implements IHasActivityPeriodsHistory, IActivityPeriodRelated
{
    use SoftDeletes;

    public const ID = 'id';
    public const COMPANY_ID = 'company_id';
    public const MODEL_NAME = 'model_name';
    public const STATE_NUMBER = 'state_number';
    public const ROUTE_ID = 'route_id';
    public const ACTIVE = 'active';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DELETED_AT = 'deleted_at';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'buses';

    /**
     * The attributes that should be cast to native types.
     *
     * @var string[]
     */
    protected $casts = [
        self::ID => 'int',
        self::COMPANY_ID => 'int',
        self::ROUTE_ID => 'int',
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
        self::MODEL_NAME,
        self::STATE_NUMBER,
        self::ROUTE_ID,
        self::ACTIVE,
    ];

    /**
     * Company to which bus belongs.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Default route that this bus serves.
     *
     * @return BelongsTo
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Installed in this bus validators.
     *
     * @return HasMany
     */
    public function validators(): HasMany
    {
        return $this->hasMany(Validator::class);
    }

    /**
     * Drivers that usually work on this bus.
     *
     * @return HasMany
     */
    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class);
    }

    /**
     * Route sheets with information when, on which route which driver serves.
     *
     * @return HasMany
     */
    public function routeSheets(): HasMany
    {
        return $this->hasMany(RouteSheet::class);
    }

    /**
     * Information about validators in this bus activity periods.
     *
     * @return HasMany
     */
    public function busesValidators(): HasMany
    {
        return $this->hasMany(BusesValidator::class);
    }

    /**
     * Returns list of activity periods.
     *
     * @return Collection|IActivityPeriod[]
     */
    public function getActivityPeriodsRecords(): Collection
    {
        return $this->busesValidators;
    }
}
