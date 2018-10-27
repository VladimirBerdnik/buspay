<?php

namespace App\Repositories;

use App\Models\CardType;
use Saritasa\LaravelRepositories\Repositories\Repository;

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
