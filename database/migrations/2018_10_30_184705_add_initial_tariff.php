<?php

use Carbon\Carbon;
use Utils\CommentsTablesMigration;

class AddInitialTariff extends CommentsTablesMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     * @throws Throwable
     */
    public function up(): void
    {
        DB::transaction(function (): void {
            $now = Carbon::now();

            // Fill tariffs table
            $tariffs = [
                1 => 'Костанай',
                2 => 'Костанай - Затобольск',
                3 => 'Костанай - Октябрьское',
                4 => 'Костанай - Лиманное',
                5 => 'Костанай - Рыбное',
                6 => 'Затобольск - Октябрьское',
                7 => 'Затобольск - Лиманное',
                8 => 'Затобольск - Рыбное',
                9 => 'Октябрьское - Рыбное',
                10 => 'Лиманное - Рыбное',
            ];

            foreach ($tariffs as $id => $tariff) {
                DB::table('tariffs')->insert([
                    'id' => $id,
                    'name' => $tariff,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Add tariff activity period record, started from 2017
            $tariffPeriodId = 1;
            DB::table('tariff_periods')->insert([
                'id' => $tariffPeriodId,
                'active_from' => Carbon::now()->year(2017)->startOfYear()->startOfDay(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $tariffFares = [
                1 => [1 => 0, 2 => 0, 3 => 80, 4 => 40, 5 => 70, 6 => 0, 7 => 80,],
                2 => [1 => 0, 2 => 0, 3 => 90, 4 => 45, 5 => 80, 6 => 0, 7 => 90,],
                3 => [1 => 0, 2 => 0, 3 => 170, 4 => 85, 5 => 160, 6 => 0, 7 => 170,],
                4 => [1 => 0, 2 => 0, 3 => 180, 4 => 90, 5 => 170, 6 => 0, 7 => 180,],
                5 => [1 => 0, 2 => 0, 3 => 200, 4 => 100, 5 => 190, 6 => 0, 7 => 200,],
                6 => [1 => 0, 2 => 0, 3 => 160, 4 => 80, 5 => 150, 6 => 0, 7 => 160,],
                7 => [1 => 0, 2 => 0, 3 => 170, 4 => 85, 5 => 160, 6 => 0, 7 => 170,],
                8 => [1 => 0, 2 => 0, 3 => 190, 4 => 95, 5 => 180, 6 => 0, 7 => 190,],
                9 => [1 => 0, 2 => 0, 3 => 60, 4 => 30, 5 => 50, 6 => 0, 7 => 60,],
                10 => [1 => 0, 2 => 0, 3 => 50, 4 => 25, 5 => 40, 6 => 0, 7 => 50,],
            ];

            foreach ($tariffFares as $tariffId => $cardTypesfares) {
                foreach ($cardTypesfares as $cardTypeId => $fare) {
                    DB::table('tariff_fares')->insert([
                        'tariff_period_id' => $tariffPeriodId,
                        'tariff_id' => $tariffId,
                        'card_type_id' => $cardTypeId,
                        'amount' => $fare,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::table('tariff_fares')->delete();
        DB::table('tariff_periods')->delete();
        DB::table('tariffs')->delete();
    }
}
