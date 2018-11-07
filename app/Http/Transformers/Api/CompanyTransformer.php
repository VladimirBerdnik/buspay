<?php

namespace App\Http\Transformers\Api;

use App\Models\Company;
use Illuminate\Contracts\Support\Arrayable;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms company to display company details.
 */
class CompanyTransformer extends BaseTransformer
{
    /**
     * Transforms company to display company details.
     *
     * @param Arrayable $model Model to transform
     *
     * @return string[]
     *
     * @throws TransformTypeMismatchException
     */
    public function transform(Arrayable $model): array
    {
        if (!$model instanceof Company) {
            throw new TransformTypeMismatchException($this, Company::class, get_class($model));
        }

        return $this->transformModel($model);
    }

    /**
     * Transforms model into appropriate format.
     *
     * @param Company $company Company to transform
     *
     * @return string[]
     */
    protected function transformModel(Company $company): array
    {
        return [
            Company::ID => $company->id,
            Company::NAME => $company->name,
            Company::BIN => $company->bin,
            Company::ACCOUNT_NUMBER => $company->account_number,
            Company::CONTACT_INFORMATION => $company->contact_information,
            // possible related records count
            'buses_count' => $company->getAttribute('buses_count'),
            'drivers_count' => $company->getAttribute('drivers_count'),
            'routes_count' => $company->getAttribute('routes_count'),
            'users_count' => $company->getAttribute('users_count'),
        ];
    }
}
