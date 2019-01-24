<?php

use App\Domain\Enums\CardTypesIdentifiers;
use App\Models\Card;
use App\Models\CardType;
use App\Models\Replenishment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReplenishmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $replenisheableCardTypesIdentifiers = [
            CardTypesIdentifiers::DEFAULT,
            CardTypesIdentifiers::CHILD,
            CardTypesIdentifiers::RETIRE,
        ];
        $maxExternalId = Replenishment::query()->max(Replenishment::EXTERNAL_ID);
        CardType::query()
            ->whereIn(CardType::ID, $replenisheableCardTypesIdentifiers)
            ->get()
            ->each(function (CardType $cardType) use (&$maxExternalId): void {
                Card::query()
                    ->where(Card::CARD_TYPE_ID, $cardType->id)
                    ->each(
                        function (Card $card) use (&$maxExternalId): void {
                            for ($i = 0; $i < 4; $i++) {
                                factory(Replenishment::class)
                                    ->make([
                                        Replenishment::CARD_ID => $card->id,
                                        Replenishment::EXTERNAL_ID => ++$maxExternalId,
                                        Replenishment::REPLENISHED_AT => Carbon::now()->subDay(random_int(1, 7) * $i),
                                    ])
                                    ->save();
                            }
                        },
                        100
                    );
            });
    }
}
