<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Saritasa\Database\Eloquent\Models\User as BaseUserModel;
use Saritasa\Enums\Gender;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Application User.
 *
 * @property int $id User identifier
 * @property string $first_name User first name
 * @property string $last_name User last name
 * @property string $email User email address
 * @property string $password User encoded password
 * @property string|null $facebook_id User facebook identifier
 * @property string|null $instagram_id|null User instagram identifier
 * @property Gender $gender User gender
 * @property string|null $avatar_url User avatar url
 * @property string|null $remember_token Remember token
 * @property Carbon $created_at Date when user was created
 * @property Carbon $updated_at Date when user was last time updated
 * @property Carbon|null $deleted_at Date when user was deleted
 */
class User extends BaseUserModel implements JWTSubject
{
    use Notifiable;

    public const DEFAULT_AVATAR = 'images/avatar/default.png';

    /**
     * Mapping of enum fields.
     *
     * @var mixed[]
     */
    protected $enums = [
        'gender' => Gender::class,
    ];

    /**
     * Mapping of defaults fields.
     *
     * @var mixed[]
     */
    protected $defaults = [
        'avatar_url' => self::DEFAULT_AVATAR,
    ];

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
