<?php

namespace App\Models;

use App\Extensions\ActivityPeriod\IHasActivityPeriod;
use App\Extensions\ActivityPeriod\IHasActivityPeriodsHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Regular bus routes.
 *
 * @property int $id Route unique identifier
 * @property string $name Route name AKA "bus number"
 * @property int $company_id Company that is currently assigned to route
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Company $company Currently assigned to route company
 * @property Collection|Bus[] $buses All buses that usually serve this route
 * @property Collection|CompaniesRoute[] $companiesRoutes Assignments of route to company information
 * @property Collection|RouteSheet[] $routeSheets All route sheets information that served this route
 */
class Route extends Model implements IHasActivityPeriodsHistory
{
    use SoftDeletes;

    public const ID = 'id';
    public const NAME = 'name';
    public const COMPANY_ID = 'company_id';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DELETED_AT = 'deleted_at';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'routes';

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
        self::CREATED_AT,
        self::UPDATED_AT,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        self::NAME,
        self::COMPANY_ID,
    ];

    /**
     * All buses that usually serve this route.
     *
     * @return HasMany
     */
    public function buses(): HasMany
    {
        return $this->hasMany(Bus::class);
    }

    /**
     * Assignments of route to company information.
     *
     * @return HasMany
     */
    public function companiesRoutes(): HasMany
    {
        return $this->hasMany(CompaniesRoute::class);
    }

    /**
     * All route sheets information that served this route.
     *
     * @return HasMany
     */
    public function routeSheets(): HasMany
    {
        return $this->hasMany(RouteSheet::class);
    }

    /**
     * Currently assigned to route company.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Returns list of activity periods.
     *
     * @return Collection|IHasActivityPeriod[]
     */
    public function getActivityPeriodsRecords(): Collection
    {
        return $this->companiesRoutes;
    }
}
