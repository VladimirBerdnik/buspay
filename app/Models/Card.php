<?php

namespace App\Models;

use App\Extensions\ActivityPeriod\IActivityPeriod;
use App\Extensions\ActivityPeriod\IActivityPeriodRelated;
use App\Extensions\ActivityPeriod\IHasActivityPeriodsHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Authentication cards that can be recognized by validators.
 *
 * @property int $id Card unique identifier
 * @property int $card_type_id Card type
 * @property integer $card_number Short card number, written on card case
 * @property integer $uin Unique card number, patched to ROM
 * @property boolean $active Is this card active or not
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property Carbon $synchronized_at When this card was synchronized with external storage last time
 *
 * @property CardType $cardType Type of card
 * @property Driver $driver Assigned to card driver
 * @property Collection|DriversCard[] $driversCards Information about this card with driver activity periods
 */
class Card extends Model implements IHasActivityPeriodsHistory, IActivityPeriodRelated
{
    use SoftDeletes;

    public const ID = 'id';
    public const CARD_TYPE_ID = 'card_type_id';
    public const CARD_NUMBER = 'card_number';
    public const UIN = 'uin';
    public const ACTIVE = 'active';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DELETED_AT = 'deleted_at';
    public const SYNCHRONIZED_AT = 'synchronized_at';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cards';

    /**
     * The attributes that should be cast to native types.
     *
     * @var string[]
     */
    protected $casts = [
        self::ID => 'int',
        self::CARD_TYPE_ID => 'int',
        self::UIN => 'int',
        self::CARD_NUMBER => 'int',
        self::ACTIVE => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var string[]
     */
    protected $dates = [
        self::CREATED_AT,
        self::UPDATED_AT,
        self::SYNCHRONIZED_AT,
        self::DELETED_AT,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        self::CARD_TYPE_ID,
        self::CARD_NUMBER,
        self::ACTIVE,
        self::SYNCHRONIZED_AT,
        self::UIN,
    ];

    /**
     * Type of card.
     *
     * @return BelongsTo
     */
    public function cardType(): BelongsTo
    {
        return $this->belongsTo(CardType::class);
    }

    /**
     * Current driver card.
     *
     * @return HasOne
     */
    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class);
    }

    /**
     * Information about this card with drivers activity periods.
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
