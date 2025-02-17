<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Payment tariffs information.
 *
 * @property int $id Tariff unique identifier
 * @property string $name Tariff name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Collection|TariffFare[] $tariffFares All fares of this tariff with tariff periods information
 */
class Tariff extends Model
{
    use SoftDeletes;

    public const ID = 'id';
    public const NAME = 'name';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DELETED_AT = 'deleted_at';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tariffs';

    /**
     * The attributes that should be cast to native types.
     *
     * @var string
     */
    protected $casts = [
        self::ID => 'int',
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
        self::NAME,
    ];

    /**
     * All fares of this tariff with tariff periods information.
     *
     * @return HasMany
     */
    public function tariffFares(): HasMany
    {
        return $this->hasMany(TariffFare::class);
    }
}
