<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Saritasa\Roles\Models\Role as SaritasaRole;

/**
 * User role in application.
 *
 * @property int $id Role unique identifier
 * @property string $slug Role machine-readable text identifier
 *
 * @property-read string $name Role displayed name
 *
 * @property Collection|User[] $users All users with this role
 */
class Role extends SaritasaRole
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
     * All users with this role.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
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
