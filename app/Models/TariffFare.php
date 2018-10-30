<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Amount of tariff fare for card type in tariff period.
 *
 * @property int $id Tariff fare unique identifier
 * @property int $tariff_period_id Tariff period for which this fare valid
 * @property int $tariff_id Tariff identifier to which this fare belongs
 * @property int $card_type_id Card type identifier to which this fare applicable
 * @property int $amount Road trip fare
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property CardType $cardType Card type to which this fare applicable
 * @property Tariff $tariff Tariff to which this fare belongs
 * @property TariffPeriod $tariffPeriod Tariff activity period during which this fare is applicable
 */
class TariffFare extends Model
{
    use SoftDeletes;

    public const ID = 'id';
    public const TARIFF_PERIOD_ID = 'tariff_period_id';
    public const TARIFF_ID = 'tariff_id';
    public const CARD_TYPE_ID = 'card_type_id';
    public const AMOUNT = 'amount';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DELETED_AT = 'deleted_at';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tariff_fares';

    /**
     * The attributes that should be cast to native types.
     *
     * @var string[]
     */
    protected $casts = [
        self::ID => 'int',
        self::TARIFF_PERIOD_ID => 'int',
        self::TARIFF_ID => 'int',
        self::CARD_TYPE_ID => 'int',
        self::AMOUNT => 'int',
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
        self::TARIFF_PERIOD_ID,
        self::TARIFF_ID,
        self::CARD_TYPE_ID,
        self::AMOUNT,
    ];

    /**
     * Card type identifier to which this fare applicable.
     *
     * @return BelongsTo
     */
    public function cardType(): BelongsTo
    {
        return $this->belongsTo(CardType::class);
    }

    /**
     * Tariff to which this fare belongs.
     *
     * @return BelongsTo
     */
    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class);
    }

    /**
     * Tariff activity period during which this fare is applicable.
     *
     * @return BelongsTo
     */
    public function tariffPeriod(): BelongsTo
    {
        return $this->belongsTo(TariffPeriod::class);
    }
}
