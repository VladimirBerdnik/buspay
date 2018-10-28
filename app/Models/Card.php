<?php

namespace App\Models;

use App\Extensions\ActivityPeriod\IHasActivityPeriod;
use App\Extensions\ActivityPeriod\IHasActivityPeriodsHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Authenticated cards that can be recognized by validators.
 *
 * @property int $id Card unique identifier
 * @property int $card_type_id Card type
 * @property string $card_number Card authentication number
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property CardType $cardType Type of card
 * @property Driver $driver Assigned to card driver
 * @property Collection|DriversCard[] $driversCards Information about this card with driver activity periods
 */
class Card extends Model implements IHasActivityPeriodsHistory
{
    use SoftDeletes;

    public const ID = 'id';
    public const CARD_TYPE_ID = 'card_type_id';
    public const CARD_NUMBER = 'card_number';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DELETED_AT = 'deleted_at';

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
        self::CARD_TYPE_ID,
        self::CARD_NUMBER,
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
     * @return Collection|IHasActivityPeriod[]
     */
    public function getActivityPeriodsRecords(): Collection
    {
        return $this->driversCards;
    }
}
