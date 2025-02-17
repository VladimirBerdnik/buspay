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
 * Historical information about cards to drivers assignments.
 *
 * @property int $id Card to driver link unique identifier
 * @property int $driver_id Linked to card driver identifier
 * @property int $card_id Linked to driver card identifier
 * @property Carbon $active_from Start date of activity period of this record
 * @property Carbon $active_to End date of activity period of this record
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Card $card Linked to driver card
 * @property Driver $driver Linked to card driver
 */
class DriversCard extends Model implements IActivityPeriod
{
    use ActivityPeriod;
    use SoftDeletes;

    public const ID = 'id';
    public const DRIVER_ID = 'driver_id';
    public const CARD_ID = 'card_id';
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
    protected $table = 'drivers_cards';

    /**
     * The attributes that should be cast to native types.
     *
     * @var string[]
     */
    protected $casts = [
        self::ID => 'int',
        self::DRIVER_ID => 'int',
        self::CARD_ID => 'int',
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
        self::DRIVER_ID,
        self::CARD_ID,
        self::ACTIVE_FROM,
        self::ACTIVE_TO,
    ];

    /**
     * Linked to driver card.
     *
     * @return BelongsTo
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Linked to card driver.
     *
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Returns attribute involved into activity period that should be presented only once at one moment of time.
     *
     * @return string
     */
    public function masterModelRelationAttribute(): string
    {
        return static::DRIVER_ID;
    }

    /**
     * Returns attribute name that is points to related activity period record model.
     *
     * @return string
     */
    public function relatedModelRelationAttribute(): string
    {
        return static::CARD_ID;
    }

    /**
     * Returns activity period master record.
     *
     * @return IActivityPeriodMaster
     */
    public function getMasterRecord(): IActivityPeriodMaster
    {
        return $this->driver;
    }

    /**
     * Returns activity period related record.
     *
     * @return IActivityPeriodRelated
     */
    public function getRelatedRecord(): IActivityPeriodRelated
    {
        return $this->card;
    }
}
