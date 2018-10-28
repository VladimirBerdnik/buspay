<?php

namespace App\Domain\Enums;

use Saritasa\Enum;

/**
 * Available authentication cards types.
 */
class CardTypes extends Enum
{
    public const DEFAULT = 'default';
    public const DRIVER = 'driver';
    public const CHILD = 'child';
    public const RETIRE = 'retire';
    public const REDUCED = 'reduced';
    public const FREE = 'free';
}
