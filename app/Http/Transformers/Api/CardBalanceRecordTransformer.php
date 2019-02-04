<?php

namespace App\Http\Transformers\Api;

use App\Domain\Dto\CardBalanceRecordData;
use Illuminate\Contracts\Support\Arrayable;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms card balance transaction to display details.
 */
class CardBalanceRecordTransformer extends BaseTransformer
{
    /**
     * Transforms card balance transaction to display details.
     *
     * @param Arrayable $model Model to transform
     *
     * @return string[]
     *
     * @throws TransformTypeMismatchException
     */
    public function transform(Arrayable $model): array
    {
        if (!$model instanceof CardBalanceRecordData) {
            throw new TransformTypeMismatchException($this, CardBalanceRecordData::class, get_class($model));
        }

        return $this->transformModel($model);
    }

    /**
     * Transforms model into appropriate format.
     *
     * @param CardBalanceRecordData $balanceRecordData Card balance transaction to transform
     *
     * @return string[]
     */
    protected function transformModel(CardBalanceRecordData $balanceRecordData): array
    {
        return [
            CardBalanceRecordData::DATE => $balanceRecordData->date->toIso8601String(),
            CardBalanceRecordData::TYPE => $balanceRecordData->type,
            CardBalanceRecordData::AMOUNT => $balanceRecordData->amount,
        ];
    }
}
