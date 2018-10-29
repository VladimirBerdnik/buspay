<?php

namespace App\Models;

use App\Extensions\ActivityPeriod\IHasActivityPeriod;
use App\Extensions\ActivityPeriod\IHasActivityPeriodsHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Transport companies with buses.
 *
 * @property int $id Company unique identifier
 * @property string $name Company name
 * @property string $account_number Account number for payments
 * @property string $contact_information Company contact information
 * @property string $bin Business identification number
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Collection|Bus[] $buses All buses that are belongs to this company
 * @property Collection|CompaniesRoute[] $companiesRoutes All route assignment information
 * @property Collection|Driver[] $drivers All drivers that are work in this company
 * @property Collection|Route[] $routes Currently assigned routes
 * @property Collection|User[] $users All company application users
 */
class Company extends Model implements IHasActivityPeriodsHistory
{
    use SoftDeletes;

    public const ID = 'id';
    public const NAME = 'name';
    public const ACCOUNT_NUMBER = 'account_number';
    public const BIN = 'bin';
    public const CONTACT_INFORMATION = 'contact_information';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DELETED_AT = 'deleted_at';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';

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
        self::NAME,
        self::ACCOUNT_NUMBER,
        self::CONTACT_INFORMATION,
        self::BIN,
    ];

    /**
     * All buses that are belongs to this company.
     *
     * @return HasMany
     */
    public function buses(): HasMany
    {
        return $this->hasMany(Bus::class);
    }

    /**
     * All route assignment information.
     *
     * @return HasMany
     */
    public function companiesRoutes(): HasMany
    {
        return $this->hasMany(CompaniesRoute::class);
    }

    /**
     * All drivers that are work in this company.
     *
     * @return HasMany
     */
    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class);
    }

    /**
     * Currently assigned routes to this company.
     *
     * @return HasMany
     */
    public function routes(): HasMany
    {
        return $this->hasMany(Route::class);
    }

    /**
     * All company application users.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Returns list of activity periods.
     *
     * @return Collection|IHasActivityPeriod[]
     */
    public function getActivityPeriodsRecords(): Collection
    {
        return $this->companiesRoutes;
    }
}
