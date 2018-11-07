<?php

namespace App\Http\Transformers\Api;

use App\Models\TariffPeriod;
use Illuminate\Contracts\Support\Arrayable;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms tariff period to display to user.
 */
class TariffPeriodTransformer extends BaseTransformer
{
    /**
     * Transforms tariff period to display to user.
     *
     * @param Arrayable $model Model to transform
     *
     * @return string[]
     *
     * @throws TransformTypeMismatchException
     */
    public function transform(Arrayable $model): array
    {
        if (!$model instanceof TariffPeriod) {
            throw new TransformTypeMismatchException($this, TariffPeriod::class, get_class($model));
        }

        return $this->transformModel($model);
    }

    /**
     * Transforms model into appropriate format.
     *
     * @param TariffPeriod $tariffPeriod Tariff period to transform
     *
     * @return string[]
     */
    protected function transformModel(TariffPeriod $tariffPeriod): array
    {
        return [
            TariffPeriod::ID => $tariffPeriod->id,
            TariffPeriod::ACTIVE_FROM => $tariffPeriod->active_from->toIso8601String(),
            TariffPeriod::ACTIVE_TO => $tariffPeriod->active_to ? $tariffPeriod->active_to->toIso8601String() : null,
        ];
    }
}
