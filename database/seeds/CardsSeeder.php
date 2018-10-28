<?php

use App\Models\Card;
use App\Models\CardType;
use Illuminate\Database\Seeder;

class CardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CardType::query()->get()->each(function (CardType $cardType) {
            factory(Card::class, 100)->create([Card::CARD_TYPE_ID => $cardType->getKey()]);
        });
    }
}
