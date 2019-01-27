<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Card transaction. Represents transport card authorization on validator device with write-off amount from card
 * balance.
 *
 * @property integer $id Transaction unique identifier
 * @property Carbon $authorized_at Date when card was authorized
 * @property integer $card_id Authorized card identifier
 * @property integer $validator_id Validator on which card was authorized
 * @property integer $tariff_id Tariff identifier with which card was authorized
 * @property integer $amount Tariff amount that was written-off from card
 * @property integer $external_id Identifier of transaction in external storage
 *
 * @property Card $card Authorized card identifier
 * @property Tariff|null $tariff Tariff identifier with which card was authorized
 * @property Validator $validator Validator on which card was authorized
 */
class Transaction extends Model
{
    public const ID = 'id';
    public const AUTHORIZED_AT = 'authorized_at';
    public const CARD_ID = 'card_id';
    public const VALIDATOR_ID = 'validator_id';
    public const TARIFF_ID = 'tariff_id';
    public const AMOUNT = 'amount';
    public const EXTERNAL_ID = 'external_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var string[]
     */
    protected $casts = [
        self::ID => 'int',
        self::CARD_ID => 'int',
        self::VALIDATOR_ID => 'int',
        self::TARIFF_ID => 'int',
        self::AMOUNT => 'int',
        self::EXTERNAL_ID => 'int',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var string[]
     */
    protected $dates = [
        self::AUTHORIZED_AT,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        self::AUTHORIZED_AT,
        self::CARD_ID,
        self::VALIDATOR_ID,
        self::TARIFF_ID,
        self::AMOUNT,
        self::EXTERNAL_ID,
    ];

    /**
     * Authorized card identifier.
     *
     * @return BelongsTo
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Tariff identifier with which card was authorized.
     *
     * @return BelongsTo
     */
    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class);
    }

    /**
     * Validator on which card was authorized.
     *
     * @return BelongsTo
     */
    public function validator(): BelongsTo
    {
        return $this->belongsTo(Validator::class);
    }
}
