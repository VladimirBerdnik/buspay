<?php

namespace App\Http\Transformers\Api;

use App\Models\Replenishment;
use Illuminate\Contracts\Support\Arrayable;
use League\Fractal\Resource\ResourceInterface;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms replenishment to display on replenishments page.
 */
class ReplenishmentTransformer extends BaseTransformer
{
    public const INCLUDE_CARD = 'card';

    /**
     * Include resources without needing it to be requested.
     *
     * @var string[]
     */
    protected $defaultIncludes = [
        self::INCLUDE_CARD,
    ];

    /**
     * Transforms card to display details.
     *
     * @var CardTransformer
     */
    private $cardTransformer;

    /**
     * Transforms replenishment to display on replenishments page.
     *
     * @param CardTransformer $cardTransformer Transforms card to display details
     */
    public function __construct(CardTransformer $cardTransformer)
    {
        $this->cardTransformer = $cardTransformer;
        $this->cardTransformer->setDefaultIncludes([]);
    }

    /**
     * Transforms replenishment to display on replenishments page.
     *
     * @param Arrayable $model Model to transform
     *
     * @return string[]
     *
     * @throws TransformTypeMismatchException
     */
    public function transform(Arrayable $model): array
    {
        if (!$model instanceof Replenishment) {
            throw new TransformTypeMismatchException($this, Replenishment::class, get_class($model));
        }

        return $this->transformModel($model);
    }

    /**
     * Transforms replenishment into appropriate format.
     *
     * @param Replenishment $replenishment Replenishment to transform
     *
     * @return string[]
     */
    protected function transformModel(Replenishment $replenishment): array
    {
        return [
            Replenishment::ID => $replenishment->id,
            Replenishment::AMOUNT => $replenishment->amount,
            Replenishment::EXTERNAL_ID => $replenishment->external_id,
            Replenishment::CARD_ID => $replenishment->card_id,
            Replenishment::REPLENISHED_AT => $replenishment->replenished_at->toIso8601String(),
            Replenishment::CREATED_AT => $replenishment->created_at->toIso8601String(),
        ];
    }

    /**
     * Includes card into transformed response.
     *
     * @param Replenishment $replenishment Replenishment to retrieve card details
     *
     * @return ResourceInterface
     */
    protected function includeCard(Replenishment $replenishment): ResourceInterface
    {
        if (!$replenishment->card) {
            return $this->null();
        }

        return $this->item($replenishment->card, $this->cardTransformer);
    }
}
