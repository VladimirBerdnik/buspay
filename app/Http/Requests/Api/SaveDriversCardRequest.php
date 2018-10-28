<?php

namespace App\Http\Requests\Api;

use App\Domain\Dto\DriversCardData;
use App\Models\DriversCard;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;

/**
 * SaveDriversCardRequest form request.
 *
 * @property-read integer $driver_id Linked to card driver identifier
 * @property-read integer $card_id Linked to driver card identifier
 * @property-read string $active_from Start date of activity period of this record
 * @property-read string|null $active_to End date of activity period of this record
 */
class SaveDriversCardRequest extends ApiRequest
{
    /**
     * Rules that should be applied to validate request.
     *
     * @return string[]|GenericRuleSet[]
     */
    public function rules(): array
    {
        return [
            DriversCard::DRIVER_ID => Rule::required()->exists('drivers', 'id')->int(),
            DriversCard::CARD_ID => Rule::required()->exists('cards', 'id')->int(),
            DriversCard::ACTIVE_FROM => Rule::required()->date(),
            DriversCard::ACTIVE_TO => Rule::nullable()->date(),
        ];
    }

    /**
     * Returns driver card details.
     *
     * @return DriversCardData
     */
    public function getDriversCardData(): DriversCardData
    {
        return new DriversCardData($this->all());
    }
}
