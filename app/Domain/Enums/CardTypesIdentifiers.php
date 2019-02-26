<?php

namespace App\Domain\Enums;

use Saritasa\Enum;

/**
 * Available authentication cards types identifiers.
 */
class CardTypesIdentifiers extends Enum
{
    public const SERVICE = 1;
    public const DRIVER = 2;
    public const DEFAULT = 3;
    public const CHILD = 4;
    public const RETIRE = 5;
    public const FREE = 6;
    public const QR = 7;
}
