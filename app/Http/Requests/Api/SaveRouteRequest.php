<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\RouteData;
use App\Models\Route;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * SaveRouteRequest form request.
 *
 * @property-read integer|null $company_id Currently assigned to route company identifier
 * @property-read string $name Route name AKA "bus number"
 */
class SaveRouteRequest extends ApiRequest
{
    /**
     * Rules that should be applied to validate request.
     *
     * @return string[]|GenericRuleSet[]
     */
    public function rules(): array
    {
        return [
            Route::COMPANY_ID => Rule::nullable()->exists('companies', 'id')->int(),
            Route::NAME => Rule::required()->string()->max(191),
        ];
    }

    /**
     * Returns route details.
     *
     * @return RouteData
     */
    public function getRouteData(): RouteData
    {
        return new RouteData($this->all());
    }
}
