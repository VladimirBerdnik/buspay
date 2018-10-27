<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * User role in application.
 *
 * @property int $id Role unique identifier
 * @property string $name Role displayed name
 * @property string $slug Role machine-readable text identifier
 *
 * @property Collection|User[] $users All users with this role
 */
class Role extends Model
{
    public const ID = 'id';
    public const NAME = 'name';
    public const SLUG = 'slug';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

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
        self::NAME,
        self::SLUG,
    ];

    /**
     * All users with this role.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
