<?php

namespace App\Domain\Dto;

use Saritasa\Dto;

/**
 * Route details.
 *
 * @property-read integer|null $company_id Currently assigned to route company identifier
 * @property-read string $name Route name AKA "bus number"
 */
class RouteData extends Dto
{
    public const COMPANY_ID = 'company_id';
    public const NAME = 'name';

    /**
     * Currently assigned to route company identifier.
     *
     * @var integer|null
     */
    protected $company_id;

    /**
     * Route name AKA "bus number".
     *
     * @var string
     */
    protected $name;
}
