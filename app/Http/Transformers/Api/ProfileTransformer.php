<?php

namespace App\Http\Transformers\Api;

use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use League\Fractal\Resource\ResourceInterface;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms user profile to display on profile page.
 */
class ProfileTransformer extends BaseTransformer
{
    /**
     * Include resources without needing it to be requested.
     *
     * @var string[]
     */
    protected $defaultIncludes = [
        'role',
        'company',
    ];

    /**
     * Transforms user profile to display on profile page.
     *
     * @param Arrayable $model Model to transform
     *
     * @return string[]
     *
     * @throws TransformTypeMismatchException
     */
    public function transform(Arrayable $model): array
    {
        if (!$model instanceof User) {
            throw new TransformTypeMismatchException($this, User::class, get_class($model));
        }

        return $this->transformModel($model);
    }

    /**
     * Transforms user into appropriate format.
     *
     * @param User $user User to transform
     *
     * @return string[]
     */
    protected function transformModel(User $user): array
    {
        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'company_id' => $user->company_id,
        ];
    }

    /**
     * Includes role into transformed response.
     *
     * @param User $user User to retrieve role details
     *
     * @return ResourceInterface
     */
    protected function includeRole(User $user): ResourceInterface
    {
        return $this->item($user->role, app(RoleTransformer::class));
    }

    /**
     * Includes company into transformed response.
     *
     * @param User $user User to retrieve company details
     *
     * @return ResourceInterface
     */
    protected function includeCompany(User $user): ResourceInterface
    {
        if (!$user->company) {
            return $this->null();
        }

        return $this->item($user->company, app(CompanyTransformer::class));
    }
}
