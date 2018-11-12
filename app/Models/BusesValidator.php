<?php

namespace App\Models;

use App\Extensions\ActivityPeriod\ActivityPeriod;
use App\Extensions\ActivityPeriod\IActivityPeriod;
use App\Extensions\ActivityPeriod\IActivityPeriodMaster;
use App\Extensions\ActivityPeriod\IActivityPeriodRelated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Historical bus to validator assignment information.
 *
 * @property int $id Validator to bus link unique identifier
 * @property int $bus_id Linked to validator bus identifier
 * @property int $validator_id Linked to bus validator identifier
 * @property Carbon $active_from Start date of activity period of this record
 * @property Carbon $active_to End date of activity period of this record
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Bus $bus Linked to validator bus
 * @property Validator $validator Linked to bus validator
 */
class BusesValidator extends Model implements IActivityPeriod
{
    use ActivityPeriod;
    use SoftDeletes;

    public const ID = 'id';
    public const BUS_ID = 'bus_id';
    public const VALIDATOR_ID = 'validator_id';
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
    protected $table = 'buses_validators';

    /**
     * The attributes that should be cast to native types.
     *
     * @var string[]
     */
    protected $casts = [
        self::ID => 'int',
        self::BUS_ID => 'int',
        self::VALIDATOR_ID => 'int',
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
        self::BUS_ID,
        self::VALIDATOR_ID,
        self::ACTIVE_FROM,
        self::ACTIVE_TO,
    ];

    /**
     * Linked to validator bus.
     *
     * @return BelongsTo
     */
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Linked to bus validator.
     *
     * @return BelongsTo
     */
    public function validator(): BelongsTo
    {
        return $this->belongsTo(Validator::class);
    }

    /**
     * Returns attribute involved into activity period that should be presented only once at one moment of time.
     *
     * @return string
     */
    public function masterModelRelationAttribute(): string
    {
        return static::VALIDATOR_ID;
    }

    /**
     * Returns attribute name that is points to related activity period record model.
     *
     * @return string
     */
    public function relatedModelRelationAttribute(): string
    {
        return static::BUS_ID;
    }

    /**
     * Returns activity period master record.
     *
     * @return IActivityPeriodMaster
     */
    public function getMasterRecord(): IActivityPeriodMaster
    {
        return $this->validator;
    }

    /**
     * Returns activity period related record.
     *
     * @return IActivityPeriodRelated
     */
    public function getRelatedRecord(): IActivityPeriodRelated
    {
        return $this->bus;
    }
}
