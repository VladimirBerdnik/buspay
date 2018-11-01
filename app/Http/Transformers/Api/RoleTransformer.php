<?php

namespace App\Http\Transformers\Api;

use App\Models\Role;
use Illuminate\Contracts\Support\Arrayable;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms role to display as user role relation.
 */
class RoleTransformer extends BaseTransformer
{
    /**
     * Transforms role to display as user role relation.
     *
     * @param Arrayable $model Model to transform
     *
     * @return string[]
     *
     * @throws TransformTypeMismatchException
     */
    public function transform(Arrayable $model): array
    {
        if (!$model instanceof Role) {
            throw new TransformTypeMismatchException($this, Role::class, get_class($model));
        }

        return $this->transformModel($model);
    }

    /**
     * Transforms model into appropriate format.
     *
     * @param Role $role Role to transform
     *
     * @return string[]
     */
    protected function transformModel(Role $role): array
    {
        return [
            'id' => $role->id,
            'slug' => $role->slug,
            'name' => $role->name,
        ];
    }
}
