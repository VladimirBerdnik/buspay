<?php

namespace App\Http\Transformers\Api;

use App\Models\Transaction;
use Illuminate\Contracts\Support\Arrayable;
use League\Fractal\Resource\ResourceInterface;
use Saritasa\Transformers\BaseTransformer;
use Saritasa\Transformers\Exceptions\TransformTypeMismatchException;

/**
 * Transforms transaction to display on transactions page.
 */
class TransactionTransformer extends BaseTransformer
{
    public const INCLUDE_CARD = 'card';
    public const INCLUDE_TARIFF = 'tariff';
    public const INCLUDE_VALIDATOR = 'validator';

    /**
     * Include resources without needing it to be requested.
     *
     * @var string[]
     */
    protected $defaultIncludes = [
        self::INCLUDE_CARD,
        self::INCLUDE_TARIFF,
        self::INCLUDE_VALIDATOR,
    ];

    /**
     * Transforms card to display details.
     *
     * @var CardTransformer
     */
    private $cardTransformer;

    /**
     * Transforms tariff where by which card was authorized.
     *
     * @var TariffTransformer
     */
    private $tariffTransformer;

    /**
     * Transforms validator on which card was authorized.
     *
     * @var ValidatorTransformer
     */
    private $validatorTransformer;

    /**
     * Transforms transaction to display on transactions page.
     *
     * @param CardTransformer $cardTransformer Transforms card to display details
     * @param TariffTransformer $tariffTransformer Transforms tariff where by which card was authorized
     * @param ValidatorTransformer $validatorTransformer Transforms validator on which card was authorized
     */
    public function __construct(
        CardTransformer $cardTransformer,
        TariffTransformer $tariffTransformer,
        ValidatorTransformer $validatorTransformer
    ) {
        $this->cardTransformer = $cardTransformer;
        $this->tariffTransformer = $tariffTransformer;
        $this->validatorTransformer = $validatorTransformer;

        $this->cardTransformer->setDefaultIncludes([CardTransformer::INCLUDE_CARD_TYPE]);
        $this->tariffTransformer->setDefaultIncludes([]);
        $this->validatorTransformer->setDefaultIncludes([ValidatorTransformer::INCLUDE_BUS]);
        $this->validatorTransformer->getBusTransformer()->setDefaultIncludes([
            BusTransformer::INCLUDE_COMPANY,
            BusTransformer::INCLUDE_ROUTE,
        ]);
    }

    /**
     * Transforms transaction to display on transactions page.
     *
     * @param Arrayable $model Model to transform
     *
     * @return string[]
     *
     * @throws TransformTypeMismatchException
     */
    public function transform(Arrayable $model): array
    {
        if (!$model instanceof Transaction) {
            throw new TransformTypeMismatchException($this, Transaction::class, get_class($model));
        }

        return $this->transformModel($model);
    }

    /**
     * Transforms transaction into appropriate format.
     *
     * @param Transaction $transaction Transaction to transform
     *
     * @return string[]
     */
    protected function transformModel(Transaction $transaction): array
    {
        return [
            Transaction::ID => $transaction->id,
            Transaction::AMOUNT => $transaction->amount,
            Transaction::EXTERNAL_ID => $transaction->external_id,
            Transaction::CARD_ID => $transaction->card_id,
            Transaction::VALIDATOR_ID => $transaction->validator_id,
            Transaction::TARIFF_ID => $transaction->tariff_id,
            Transaction::AUTHORIZED_AT => $transaction->authorized_at->toIso8601String(),
        ];
    }

    /**
     * * Transforms card to display details.
     *
     * @return CardTransformer
     */
    public function getCardTransformer(): CardTransformer
    {
        return $this->cardTransformer;
    }

    /**
     * * Transforms tariff where by which card was authorized.
     *
     * @return TariffTransformer
     */
    public function getTariffTransformer(): TariffTransformer
    {
        return $this->tariffTransformer;
    }

    /**
     * * Transforms validator on which card was authorized.
     *
     * @return ValidatorTransformer
     */
    public function getValidatorTransformer(): ValidatorTransformer
    {
        return $this->validatorTransformer;
    }

    /**
     * Includes card into transformed response.
     *
     * @param Transaction $transaction Transaction to retrieve card details
     *
     * @return ResourceInterface
     */
    protected function includeCard(Transaction $transaction): ResourceInterface
    {
        if (!$transaction->card) {
            return $this->null();
        }

        return $this->item($transaction->card, $this->cardTransformer);
    }

    /**
     * Includes validator into transformed response.
     *
     * @param Transaction $transaction Transaction to retrieve validator details
     *
     * @return ResourceInterface
     */
    protected function includeValidator(Transaction $transaction): ResourceInterface
    {
        if (!$transaction->validator) {
            return $this->null();
        }

        return $this->item($transaction->validator, $this->validatorTransformer);
    }

    /**
     * Includes tariff into transformed response.
     *
     * @param Transaction $transaction Transaction to retrieve tariff details
     *
     * @return ResourceInterface
     */
    protected function includeTariff(Transaction $transaction): ResourceInterface
    {
        if (!$transaction->tariff) {
            return $this->null();
        }

        return $this->item($transaction->tariff, $this->tariffTransformer);
    }
}
