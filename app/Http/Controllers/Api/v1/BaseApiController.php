<?php

namespace App\Http\Controllers\Api\v1;

use Dingo\Api\Http\Response;
use Illuminate\Support\Collection;
use Saritasa\LaravelControllers\Api\BaseApiController as SaritasaBaseApiController;
use Saritasa\Transformers\IDataTransformer;

/**
 * Api controller with improved method to return collection of items.
 */
class BaseApiController extends SaritasaBaseApiController
{
    /**
     * Returns ready to response transformed data.
     *
     * @param mixed $data Data to transform. Can be single model, collection or paginator.
     * @param IDataTransformer|null $transformer Raw data into response format transformer
     *
     * @return Response
     */
    protected function json($data, ?IDataTransformer $transformer = null): Response
    {
        if ($data instanceof Collection) {
            return $this->response->collection($data, $transformer ?? $this->transformer);
        }

        return parent::json($data, $transformer);
    }
}
