<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Smart devices that can authorize payment cards.
 *
 * @property int $id Validator unique identifier
 * @property string $serial_number Validator serial number
 * @property int $bus_id Identifier of bus where this validator installed
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Collection|Bus[] $buses Buses on which this validator was installed
 * @property Bus $bus Bus where this validator installed
 */
class Validator extends Model
{
    use SoftDeletes;

    public const ID = 'id';
    public const BUS_ID = 'bus_id';
    public const SERIAL_NUMBER = 'serial_number';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DELETED_AT = 'deleted_at';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'validators';

    /**
     * The attributes that should be cast to native types.
     *
     * @var string[]
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
        self::SERIAL_NUMBER,
        self::BUS_ID,
    ];

    /**
     * Buses on which this validator was.
     *
     * @return BelongsToMany
     */
    public function buses(): BelongsToMany
    {
        return $this->belongsToMany(Bus::class, 'buses_validators')
            ->withPivot(
                BusesValidator::ID,
                BusesValidator::ACTIVE_FROM,
                BusesValidator::ACTIVE_TO,
                BusesValidator::DELETED_AT
            )
            ->withTimestamps();
    }

    /**
     * Bus where this validator installed.
     *
     * @return BelongsTo
     */
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }
}
