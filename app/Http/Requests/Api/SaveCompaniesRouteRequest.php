<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\CompaniesRouteData;
use App\Models\CompaniesRoute;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * SaveCompaniesRouteRequest form request.
 *
 * @property-read integer $company_id Linked to route company identifier
 * @property-read integer $route_id Linked to company route identifier
 * @property-read string $active_from Start date of activity period of this record
 * @property-read string|null $active_to End date of activity period of this record
 */
class SaveCompaniesRouteRequest extends ApiRequest
{
    /**
     * Rules that should be applied to validate request.
     *
     * @return string[]|GenericRuleSet[]
     */
    public function rules(): array
    {
        return [
            CompaniesRoute::COMPANY_ID => Rule::required()->exists('companies', 'id')->int(),
            CompaniesRoute::ROUTE_ID => Rule::required()->exists('routes', 'id')->int(),
            CompaniesRoute::ACTIVE_FROM => Rule::required()->date(),
            CompaniesRoute::ACTIVE_TO => Rule::nullable()->date(),
        ];
    }

    /**
     * Returns company route details.
     *
     * @return CompaniesRouteData
     */
    public function getCompaniesRouteData(): CompaniesRouteData
    {
        return new CompaniesRouteData($this->all());
    }
}
