<?php

namespace App\Domain\Dto;

use Saritasa\Dto;

/**
 * Card Type details.
 *
 * @property-read string $slug Type machine-readable text identifier
 */
class CardTypeData extends Dto
{
    public const SLUG = 'slug';

    /**
     * Type machine-readable text identifier.
     *
     * @var string
     */
    protected $slug;
}
