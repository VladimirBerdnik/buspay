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
 * @property Carbon $replenished_at Date when card was replenished
 * @property Carbon $created_at When record was created
 * @property Carbon $updated_at When record was updated
 *
 * @property Card $card Card that was replenished
 */
class Replenishment extends Model
{
    public const ID = 'id';
    public const CARD_ID = 'card_id';
    public const AMOUNT = 'amount';
    public const REPLENISHED_AT = 'replenished_at';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

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
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var string[]
     */
    protected $dates = [
        self::REPLENISHED_AT,
        self::CREATED_AT,
        self::UPDATED_AT,
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
