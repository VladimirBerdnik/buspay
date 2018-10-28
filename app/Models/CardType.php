<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Possible authenticated card types.
 *
 * @property int $id Type unique identifier
 * @property string $slug Type machine-readable text identifier
 * @property string $deleted_at
 *
 * @property-read string $name Card type displayed name
 *
 * @property Collection|Card[] $cards All cards with this type
 * @property Collection|TariffFare[] $tariffFares All tariff fares for this card type
 */
class CardType extends Model
{
    use SoftDeletes;

    public const ID = 'id';
    public const NAME = 'name';
    public const SLUG = 'slug';
    public const DELETED_AT = 'deleted_at';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'card_types';

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
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        self::SLUG,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var string[]
     */
    protected $appends = [
        self::NAME,
    ];

    /**
     * The attributes that should be visible in serialization.
     *
     * @var string[]
     */
    protected $visible = [
        self::ID,
        self::NAME,
        self::SLUG,
    ];

    /**
     * All cards with this type.
     *
     * @return HasMany
     */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    /**
     * All tariff fares for this card type.
     *
     * @return HasMany
     */
    public function tariffFares(): HasMany
    {
        return $this->hasMany(TariffFare::class);
    }

    /**
     * Substitutes name attribute retrieving.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return trans("roles.{$this->slug}");
    }
}
