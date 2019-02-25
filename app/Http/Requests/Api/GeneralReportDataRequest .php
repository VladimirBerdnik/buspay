<?php

namespace App\Http\Requests\Api;

use App\Domain\Reports\GeneralReportFields;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * Request with parameters to retrieve sorted filtered data of general report.
 *
 * @property-read string[]|null $fields List of requested for report fields
 */
class GeneralReportDataRequest extends FilteredListRequest
{
    protected const REPORT_FIELDS_ATTRIBUTE = 'fields';

    /**
     * Rules that should be applied to validate request.
     *
     * @return string[]|GenericRuleSet[]
     */
    public function rules(): array
    {
        return array_merge(
            parent:: rules(),
            [
                static::REPORT_FIELDS_ATTRIBUTE => Rule::array(),
                static::REPORT_FIELDS_ATTRIBUTE . '.*' => Rule::string()->in(GeneralReportFields::getConstants()),
            ]
        );
    }
}
