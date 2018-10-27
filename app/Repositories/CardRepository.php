<?php

namespace App\Repositories;

use App\Models\Card;
use Saritasa\LaravelRepositories\Repositories\Repository;

/**
 * Card records storage.
 */
class CardRepository extends Repository
{
    /**
     * FQN model name of the repository.
     *
     * @var string
     */
    protected $modelClass = Card::class;
}
