<?php

use App\Models\CardType;
use App\Models\Tariff;
use App\Models\TariffFare;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TariffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $baseFare = 100;
        $now = Carbon::now();
        $tariff = Tariff::query()
            ->whereDate(Tariff::ACTIVE_FROM, '>=', $now)
            ->whereDate(Tariff::ACTIVE_TO, '<=', $now)
            ->first();

        if ($tariff) {
            throw new LogicException("Tariff already exists [ID = {$tariff->getKey()}], no need to generate new one");
        }

        /**
         * Created fake tariff.
         *
         * @var Tariff $tariff
         */
        $tariff = factory(Tariff::class)->create();
        $basicCardTypeSlug = config('buspay.fare.base_card_type_slug');
        $basicCardType = CardType::query()->where(CardType::SLUG, $basicCardTypeSlug)->firstOrFail();

        factory(TariffFare::class)->create([
            TariffFare::TARIFF_ID => $tariff->getKey(),
            TariffFare::CARD_TYPE_ID => $basicCardType->getKey(),
            TariffFare::AMOUNT => $baseFare,
        ]);

        $preferentialCardTypesSlugs = config('buspay.fare.preferential_card_types');
        $discountStep = round($baseFare / count($preferentialCardTypesSlugs));
        foreach ($preferentialCardTypesSlugs as $index => $preferentialCardTypeSlug) {
            $preferentialCardType = CardType::query()->where(CardType::SLUG, $preferentialCardTypeSlug)->firstOrFail();
            $preferentialFare = $baseFare - $discountStep * ($index + 1);
            factory(TariffFare::class)->create([
                TariffFare::TARIFF_ID => $tariff->getKey(),
                TariffFare::CARD_TYPE_ID => $preferentialCardType->getKey(),
                TariffFare::AMOUNT => $preferentialFare,
            ]);
        }
    }
}
