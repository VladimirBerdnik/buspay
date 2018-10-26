<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Saritasa\Database\Eloquent\Models\User as BaseUserModel;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Application User.
 *
 * @property int $id User identifier
 * @property string $first_name User first name
 * @property string $last_name User last name
 * @property string $email User email address
 * @property string $password User encoded password
 * @property string|null $remember_token Remember token
 * @property Carbon $created_at Date when user was created
 * @property Carbon $updated_at Date when user was last time updated
 * @property Carbon|null $deleted_at Date when user was deleted
 */
class User extends BaseUserModel implements JWTSubject
{
    use Notifiable;

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
