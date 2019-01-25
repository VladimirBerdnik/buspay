<?php

namespace App\Domain\Enums;

use Saritasa\Enum;

/**
 * Available card transactions types.
 */
class CardTransactionsTypes extends Enum
{
    public const REPLENISHMENT = 'replenishment';
    public const WRITE_OFF = 'writeOff';
}
