<?php

namespace App\Domain\Enums;

use Saritasa\Enum;

/**
 * Available user roles.
 */
class Roles extends Enum
{
    public const ADMIN = 'admin';
    public const OPERATOR = 'operator';
    public const GOVERNMENT = 'government';
}
