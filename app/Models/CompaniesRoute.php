<?php

namespace App\Models;

use App\Domain\IBelongsToCompany;
use App\Extensions\ActivityPeriod\ActivityPeriod;
use App\Extensions\ActivityPeriod\IActivityPeriod;
use App\Extensions\ActivityPeriod\IActivityPeriodMaster;
use App\Extensions\ActivityPeriod\IActivityPeriodRelated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Historical information about transport companies to routes assignments.
 *
 * @property int $id Route to company link unique identifier
 * @property int $company_id Linked to route company identifier
 * @property int $route_id Linked to company route identifier
 * @property Carbon $active_from Start date of activity period of this record
 * @property Carbon $active_to End date of activity period of this record
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Company $company Linked to route company
 * @property Route $route Linked to company route
 */
class CompaniesRoute extends Model implements IActivityPeriod, IBelongsToCompany
{
    use ActivityPeriod;
    use SoftDeletes;

    public const ID = 'id';
    public const COMPANY_ID = 'company_id';
    public const ROUTE_ID = 'route_id';
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
    protected $table = 'companies_routes';

    /**
     * The attributes that should be cast to native types.
     *
     * @var string[]
     */
    protected $casts = [
        self::ID => 'int',
        self::COMPANY_ID => 'int',
        self::ROUTE_ID => 'int',
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
        self::ACTIVE_FROM,
        self::ACTIVE_TO,
    ];

    /**
     * Linked to route company.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Linked to company route.
     *
     * @return BelongsTo
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Returns attribute involved into activity period that should be presented only once at one moment of time.
     *
     * @return string
     */
    public function masterModelRelationAttribute(): string
    {
        return static::ROUTE_ID;
    }

    /**
     * Returns attribute name that is points to related activity period record model.
     *
     * @return string
     */
    public function relatedModelRelationAttribute(): string
    {
        return static::COMPANY_ID;
    }

    /**
     * Returns activity period master record.
     *
     * @return IActivityPeriodMaster
     */
    public function getMasterRecord(): IActivityPeriodMaster
    {
        return $this->route;
    }

    /**
     * Returns activity period related record.
     *
     * @return IActivityPeriodRelated
     */
    public function getRelatedRecord(): IActivityPeriodRelated
    {
        return $this->company;
    }
}
