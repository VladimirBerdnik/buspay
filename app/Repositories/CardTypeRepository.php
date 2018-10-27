<?php

namespace App\Repositories;

use App\Extensions\PredefinedModelClassRepository as Repository;
use App\Models\CardType;

/**
 * CardType records storage.
 */
class CardTypeRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = CardType::class;
}
