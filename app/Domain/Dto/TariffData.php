<?php

namespace App\Domain\Dto;

use Saritasa\Dto;

/**
 * Tariff details.
 *
 * @property-read string $active_from Start date of activity period of this record
 * @property-read string|null $active_to End date of activity period of this record
 */
class TariffData extends Dto
{
    public const ACTIVE_FROM = 'active_from';
    public const ACTIVE_TO = 'active_to';

    /**
     * Start date of activity period of this record.
     *
     * @var string
     */
    protected $active_from;

    /**
     * End date of activity period of this record.
     *
     * @var string|null
     */
    protected $active_to;
}
