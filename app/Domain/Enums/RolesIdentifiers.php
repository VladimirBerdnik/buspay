<?php

namespace App\Domain\Enums;

use Saritasa\Enum;

/**
 * Available user roles identifiers.
 */
class RolesIdentifiers extends Enum
{
    public const ADMIN = 1;
    public const SUPPORT = 2;
    public const OPERATOR = 3;
    public const GOVERNMENT = 4;
}
