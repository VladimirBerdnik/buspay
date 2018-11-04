<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\CompanyData;
use App\Models\Company;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * Request to save company details.
 *
 * @property-read string $name Company name
 * @property-read string $bin Business identification number
 * @property-read string $account_number Account number for payments
 * @property-read string $contact_information Company contact information
 */
class SaveCompanyRequest extends ApiRequest
{
    /**
     * Rules that should be applied to validate request.
     *
     * @return string[]|GenericRuleSet[]
     */
    public function rules(): array
    {
        return [
            Company::NAME => Rule::required()->string()->max(64),
            Company::ACCOUNT_NUMBER => Rule::required()->string()->max(20),
            Company::BIN => Rule::required()->string()->max(12),
            Company::CONTACT_INFORMATION => Rule::required()->string()->max(191),
        ];
    }

    /**
     * Returns company details.
     *
     * @return CompanyData
     */
    public function getCompanyData(): CompanyData
    {
        return new CompanyData($this->all());
    }
}
