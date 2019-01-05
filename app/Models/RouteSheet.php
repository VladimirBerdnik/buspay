<?php

namespace App\Models;

use App\Domain\IBelongsToCompany;
use App\Extensions\ActivityPeriod\ActivityPeriod;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Route sheet with driver to bus and route assignment historical information.
 *
 * @property int $id Route sheet unique identifier
 * @property int $company_id Company identifier to which this route sheet belongs to
 * @property int $route_id Bus route identifier, which served the driver on the bus
 * @property int $bus_id Bus identifier that is on route
 * @property int $driver_id Driver identifier that is on bus on route
 * @property Carbon $active_from Start date of activity period of this record
 * @property Carbon $active_to End date of activity period of this record
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Company $company Company to which this route sheet belongs to
 * @property Bus $bus Bus that is on route
 * @property Driver $driver Driver that is on bus on route
 * @property Route $route Bus route, which served the driver on the bus
 */
class RouteSheet extends Model implements IBelongsToCompany
{
    use ActivityPeriod;
    use SoftDeletes;

    public const ID = 'id';
    public const COMPANY_ID = 'company_id';
    public const ROUTE_ID = 'route_id';
    public const BUS_ID = 'bus_id';
    public const DRIVER_ID = 'driver_id';
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
    protected $table = 'route_sheets';

    /**
     * The attributes that should be cast to native types.
     *
     * @var string[]
     */
    protected $casts = [
        self::ID => 'int',
        self::ROUTE_ID => 'int',
        self::BUS_ID => 'int',
        self::DRIVER_ID => 'int',
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
        self::COMPANY_ID,
        self::ROUTE_ID,
        self::BUS_ID,
        self::DRIVER_ID,
        self::ACTIVE_FROM,
        self::ACTIVE_TO,
    ];

    /**
     * Company to which this route sheet belongs to.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Bus that is on route.
     *
     * @return BelongsTo
     */
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Driver that is on bus on route.
     *
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Bus route, which served the driver on the bus.
     *
     * @return BelongsTo
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }
}
