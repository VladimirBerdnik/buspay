<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Buses - assets of transport companies. Can serve route.
 *
 * @property int $id Bus unique identifier
 * @property int $company_id Company identifier, to which this bus belongs
 * @property string $model_name Name of bus model
 * @property string $state_number Bus state number
 * @property string $description Bus description or notes
 * @property int $route_id Usual route identifier, on which this bus is
 * @property int $active Does this bus works or not, can be assigned to route or not
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Company $company Company to which bus belongs
 * @property Route $route Default route that this bus serves
 * @property Collection|Validator[] $validators Validators that was assigned to this bus
 * @property Collection|Driver[] $drivers Drivers that usually work on this bus
 * @property Collection|RouteSheet[] $routeSheets Route sheets with information when, on which route which driver serves
 */
class Bus extends Model
{
    use SoftDeletes;

    public const ID = 'id';
    public const COMPANY_ID = 'company_id';
    public const MODEL_NAME = 'model_name';
    public const STATE_NUMBER = 'state_number';
    public const DESCRIPTION = 'description';
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
        self::ACTIVE => 'int',
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
        self::DESCRIPTION,
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
     * Validators that was assigned to this bus.
     *
     * @return BelongsToMany
     */
    public function validators(): BelongsToMany
    {
        return $this->belongsToMany(Validator::class, 'buses_validators')
            ->withPivot(
                BusesValidator::ID,
                BusesValidator::ACTIVE_FROM,
                BusesValidator::ACTIVE_TO,
                BusesValidator::DELETED_AT
            )
            ->withTimestamps();
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
}
