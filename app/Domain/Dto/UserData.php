<?php

namespace App\Domain\Dto;

use Saritasa\Dto;

/**
 * User details.
 *
 * @property-read integer $role_id User role identifier
 * @property-read integer|null $company_id Company identifier in which user works
 * @property-read string $first_name User first name
 * @property-read string $last_name User last name
 * @property-read string $email User email address
 * @property-read string $password User password
 */
class UserData extends Dto
{
    public const ROLE_ID = 'role_id';
    public const COMPANY_ID = 'company_id';
    public const FIRST_NAME = 'first_name';
    public const LAST_NAME = 'last_name';
    public const EMAIL = 'email';
    public const PASSWORD = 'password';

    /**
     * User role identifier.
     *
     * @var integer
     */
    protected $role_id;

    /**
     * Company identifier in which user works.
     *
     * @var integer|null
     */
    protected $company_id;

    /**
     * User first name.
     *
     * @var string
     */
    protected $first_name;

    /**
     * User last name.
     *
     * @var string
     */
    protected $last_name;

    /**
     * User email address.
     *
     * @var string
     */
    protected $email;

    /**
     * User password.
     *
     * @var string
     */
    protected $password;
}
