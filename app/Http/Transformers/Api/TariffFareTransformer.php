<?php

namespace App\Http\Transformers\Api;

use App\Models\TariffFare;
use Illuminate\Contracts\Support\Arrayable;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms tariff fare to display to user.
 */
class TariffFareTransformer extends BaseTransformer
{
    /**
     * Transforms tariff fare to display to user.
     *
     * @param Arrayable $model Model to transform
     *
     * @return string[]
     *
     * @throws TransformTypeMismatchException
     */
    public function transform(Arrayable $model): array
    {
        if (!$model instanceof TariffFare) {
            throw new TransformTypeMismatchException($this, TariffFare::class, get_class($model));
        }

        return $this->transformModel($model);
    }

    /**
     * Transforms model into appropriate format.
     *
     * @param TariffFare $tariffFare Tariff Fare to transform
     *
     * @return string[]
     */
    protected function transformModel(TariffFare $tariffFare): array
    {
        return [
            TariffFare::ID => $tariffFare->id,
            TariffFare::TARIFF_ID => $tariffFare->tariff_id,
            TariffFare::TARIFF_PERIOD_ID => $tariffFare->tariff_period_id,
            TariffFare::CARD_TYPE_ID => $tariffFare->card_type_id,
            TariffFare::AMOUNT => $tariffFare->amount,
        ];
    }
}
