<?php

namespace App\Http\Transformers\Api;

use App\Domain\Dto\Reports\GeneralReportRow;
use Illuminate\Contracts\Support\Arrayable;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms general report row to display details.
 */
class GeneralReportTransformer extends BaseTransformer
{
    /**
     * Transforms role to display details.
     *
     * @param Arrayable $model Model to transform
     *
     * @return string[]
     *
     * @throws TransformTypeMismatchException
     */
    public function transform(Arrayable $model): array
    {
        if (!$model instanceof GeneralReportRow) {
            throw new TransformTypeMismatchException($this, GeneralReportRow::class, get_class($model));
        }

        return $this->transformModel($model);
    }

    /**
     * Transforms model into appropriate format.
     *
     * @param GeneralReportRow $reportRow Row with report item details
     *
     * @return string[]
     */
    protected function transformModel(GeneralReportRow $reportRow): array
    {
        return $reportRow->toArray();
    }
}
