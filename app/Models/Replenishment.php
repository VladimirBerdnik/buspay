<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Replenishment model. Represents filling of card deposit with some amount of money.
 *
 * @property int $id Replenishment unique identifier
 * @property int $card_id Identifier of card that was replenished
 * @property float $amount Amount of card replenishment
 * @property int $external_id Identifier of replenishment in external storage
 * @property Carbon $replenished_at Date when card was replenished
 *
 * @property Card $card Card that was replenished
 */
class Replenishment extends Model
{
    public const ID = 'id';
    public const CARD_ID = 'card_id';
    public const AMOUNT = 'amount';
    public const REPLENISHED_AT = 'replenished_at';
    public const EXTERNAL_ID = 'external_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Name of table where this model is stored.
     *
     * @var string
     */
    protected $table = 'replenishments';

    /**
     * The attributes that should be cast to native types.
     *
     * @var string[]
     */
    protected $casts = [
        self::ID => 'int',
        self::CARD_ID => 'int',
        self::AMOUNT => 'float',
        self::EXTERNAL_ID => 'int',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var string[]
     */
    protected $dates = [
        self::REPLENISHED_AT,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        self::CARD_ID,
        self::AMOUNT,
        self::REPLENISHED_AT,
        self::EXTERNAL_ID,
    ];

    /**
     * Related card that was replenished.
     *
     * @return BelongsTo
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
