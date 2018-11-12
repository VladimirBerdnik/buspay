<?php

use App\Models\Bus;
use App\Models\BusesValidator;
use App\Models\Validator;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ValidatorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $buses = Bus::query()->inRandomOrder()->get();
        factory(Validator::class, 400)->create()
            ->each(function (Validator $validator) use ($buses) {
                if ($buses->isNotEmpty()) {
                    $busToAssign = $buses->pop();
                    $validator->bus_id = $busToAssign->getKey();
                    $validator->save();
                    BusesValidator::query()->create([
                        BusesValidator::BUS_ID => $validator->bus_id,
                        BusesValidator::VALIDATOR_ID => $validator->id,
                        BusesValidator::ACTIVE_FROM => Carbon::now(),
                    ]);
                }
            });
    }
}
