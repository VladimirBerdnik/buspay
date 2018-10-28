<?php

namespace App\Domain\Dto;

use Saritasa\Dto;

/**
 * Company to Route assignment details.
 *
 * @property-read integer $company_id Linked to route company identifier
 * @property-read integer $route_id Linked to company route identifier
 * @property-read string $active_from Start date of activity period of this record
 * @property-read string|null $active_to End date of activity period of this record
 */
class CompaniesRouteData extends Dto
{
    public const COMPANY_ID = 'company_id';
    public const ROUTE_ID = 'route_id';
    public const ACTIVE_FROM = 'active_from';
    public const ACTIVE_TO = 'active_to';

    /**
     * Linked to route company identifier.
     *
     * @var integer
     */
    protected $company_id;

    /**
     * Linked to company route identifier.
     *
     * @var integer
     */
    protected $route_id;

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
