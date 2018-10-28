<?php

namespace App\Models;

use App\Extensions\ActivityPeriod\HasActivityPeriod;
use App\Extensions\ActivityPeriod\IHasActivityPeriod;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Route sheet with driver to bus and route assignment historical information.
 *
 * @property int $id Route sheet unique identifier
 * @property int $route_id Bus route identifier, which served the driver on the bus
 * @property int $bus_id Bus identifier that is on route
 * @property int $driver_id Driver identifier that is on bus on route
 * @property bool $temporary Is this route sheet temporary (reserve) or not
 * @property Carbon $active_from Start date of activity period of this record
 * @property Carbon $active_to End date of activity period of this record
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Bus $bus Bus that is on route
 * @property Driver $driver Driver that is on bus on route
 * @property Route $route Bus route, which served the driver on the bus
 */
class RouteSheet extends Model implements IHasActivityPeriod
{
    use HasActivityPeriod;
    use SoftDeletes;

    public const ID = 'id';
    public const ROUTE_ID = 'route_id';
    public const BUS_ID = 'bus_id';
    public const DRIVER_ID = 'driver_id';
    public const TEMPORARY = 'temporary';
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
        self::TEMPORARY => 'bool',
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
        self::ROUTE_ID,
        self::BUS_ID,
        self::DRIVER_ID,
        self::TEMPORARY,
        self::ACTIVE_FROM,
        self::ACTIVE_TO,
    ];

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
