<?php

use App\Domain\Enums\CardTypesIdentifiers;
use App\Models\Card;
use App\Models\Company;
use App\Models\Driver;
use App\Models\DriversCard;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DriversSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cards = Card::query()->where(Card::CARD_TYPE_ID, CardTypesIdentifiers::DRIVER)->inRandomOrder()->get();
        Company::query()->get()->each(function (Company $company) use ($cards) {
            factory(Driver::class, random_int(20, 50))->make([])
                ->each(function (Driver $driver) use ($cards, $company) {
                    $card = random_int(0, 6) ? $cards->pop() : null;
                    $driver->bus_id = random_int(0, 6) ? $company->buses()->inRandomOrder()->first()->getKey() : null;
                    $driver->company_id = $company->getKey();
                    $driver->card_id = $card ? $card->getKey() : null;
                    $driver->save();

                    if ($card) {
                        DriversCard::query()->create([
                            DriversCard::CARD_ID => $card->getKey(),
                            DriversCard::DRIVER_ID => $driver->getKey(),
                            DriversCard::ACTIVE_FROM => Carbon::now(),
                        ]);
                    }
                });
        });
    }
}
