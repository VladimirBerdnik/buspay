<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Saritasa\Database\Eloquent\Models\User as BaseUserModel;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * User of application.
 *
 * @property int $id User unique identifier
 * @property int $role_id User role identifier
 * @property int $company_id Company identifier in which user works
 * @property string $first_name User first name
 * @property string $last_name User last name
 * @property string $email User email address
 * @property string $password User password
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Company $company Company in which user works
 * @property Role $role User role
 */
class User extends BaseUserModel implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;

    public const ID = 'id';
    public const ROLE_ID = 'role_id';
    public const COMPANY_ID = 'company_id';
    public const FIRST_NAME = 'first_name';
    public const LAST_NAME = 'last_name';
    public const EMAIL = 'email';
    public const PASSWORD = 'password';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DELETED_AT = 'deleted_at';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that should be cast to native types.
     *
     * @var string[]
     */
    protected $casts = [
        self::ID => 'int',
        self::ROLE_ID => 'int',
        self::COMPANY_ID => 'int',
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
     * The attributes that should be hidden for serialization.
     *
     * @var string[]
     */
    protected $hidden = [
        self::PASSWORD,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        self::ROLE_ID,
        self::COMPANY_ID,
        self::FIRST_NAME,
        self::LAST_NAME,
        self::EMAIL,
        self::PASSWORD,
    ];

    /**
     * Company in which user works.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * User role.
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getJWTIdentifier(): string
    {
        return $this->getKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
