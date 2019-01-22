<?php

use App\Models\Bus;
use App\Models\BusesValidator;
use App\Models\Card;
use App\Models\CardType;
use App\Models\CompaniesRoute;
use App\Models\Company;
use App\Models\Driver;
use App\Models\DriversCard;
use App\Models\Replenishment;
use App\Models\Route;
use App\Models\RouteSheet;
use App\Models\User;
use App\Models\Validator;
use Carbon\Carbon;
use Faker\Generator;
use Illuminate\Database\Eloquent\Factory;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/**
 * Fake models factory.
 *
 * @var Factory $factory
 */
$factory->define(Bus::class, function (Generator $faker, array $parameters) {
    return [
        Bus::MODEL_NAME => $faker->linuxProcessor,
        Bus::STATE_NUMBER => random_int(100, 999) . str_random(2),
        // Have to be filled outside with valid business-logic value
        Bus::ACTIVE => $parameters[Bus::ACTIVE] ?? true,
        Bus::ROUTE_ID => $parameters[Bus::ROUTE_ID] ?? null,
        Bus::COMPANY_ID => $parameters[Bus::COMPANY_ID] ?? null,
    ];
});

$factory->define(RouteSheet::class, function (Generator $faker, array $parameters) {
    return [
        // Have to be filled outside with valid business-logic value
        RouteSheet::COMPANY_ID => $parameters[RouteSheet::COMPANY_ID] ?? null,
        RouteSheet::ROUTE_ID => $parameters[RouteSheet::ROUTE_ID] ?? null,
        RouteSheet::BUS_ID => $parameters[RouteSheet::BUS_ID] ?? null,
        RouteSheet::DRIVER_ID => $parameters[RouteSheet::DRIVER_ID] ?? null,
        RouteSheet::ACTIVE_FROM => $parameters[RouteSheet::ACTIVE_FROM] ?? null,
        RouteSheet::ACTIVE_TO => $parameters[RouteSheet::ACTIVE_TO] ?? null,
    ];
});

$factory->define(BusesValidator::class, function (Generator $faker, array $parameters) {
    return [
        BusesValidator::ACTIVE_FROM => Carbon::now(),
        BusesValidator::ACTIVE_TO => Carbon::now()->addYear(),
        // Have to be filled outside with valid business-logic value
        BusesValidator::BUS_ID => $parameters[BusesValidator::BUS_ID] ?? null,
        BusesValidator::VALIDATOR_ID => $parameters[BusesValidator::VALIDATOR_ID] ?? null,
    ];
});

$factory->define(Card::class, function (Generator $faker, array $parameters) {
    return [
        Card::CARD_TYPE_ID => $parameters[Card::CARD_TYPE_ID] ?? CardType::query()->inRandomOrder()->first()->getKey(),
        Card::CARD_NUMBER => $faker->unique()->randomNumber(8, true),
        Card::ACTIVE => $faker->boolean(75),
        Card::SYNCHRONIZED_AT => Carbon::now(),
        // Just make random number little longer
        Card::UIN => $faker->unique()->randomNumber(8, true) * 12345,
    ];
});

$factory->define(Company::class, function (Generator $faker) {
    return [
        Company::NAME => $faker->company,
        Company::ACCOUNT_NUMBER => $faker->randomNumber(6, true),
        Company::CONTACT_INFORMATION => $faker->phoneNumber,
        Company::BIN => str_random(12),
    ];
});

$factory->define(CompaniesRoute::class, function (Generator $faker, array $parameters) {
    return [
        CompaniesRoute::ACTIVE_FROM => Carbon::now(),
        CompaniesRoute::ACTIVE_TO => Carbon::now()->addYear(),
        // Have to be filled outside with valid business-logic value
        CompaniesRoute::COMPANY_ID => $parameters[CompaniesRoute::COMPANY_ID] ?? null,
        CompaniesRoute::ROUTE_ID => $parameters[CompaniesRoute::ROUTE_ID] ?? null,
    ];
});

$factory->define(Driver::class, function (Generator $faker, array $parameters) {
    return [
        Driver::FULL_NAME => $faker->firstName . ' ' . $faker->lastName,
        // Have to be filled outside with valid business-logic value
        Driver::ACTIVE => $parameters[Driver::ACTIVE] ?? true,
        Driver::BUS_ID => $parameters[Driver::BUS_ID] ?? null,
        Driver::COMPANY_ID => $parameters[Driver::COMPANY_ID] ?? null,
        Driver::CARD_ID => $parameters[Driver::CARD_ID] ?? null,
    ];
});

$factory->define(DriversCard::class, function (Generator $faker, array $parameters) {
    return [
        DriversCard::ACTIVE_FROM => Carbon::now(),
        DriversCard::ACTIVE_TO => Carbon::now()->addYear(),
        // Have to be filled outside with valid business-logic value
        DriversCard::DRIVER_ID => $parameters[DriversCard::DRIVER_ID] ?? null,
        DriversCard::CARD_ID => $parameters[DriversCard::CARD_ID] ?? null,
    ];
});

$factory->define(Route::class, function (Generator $faker, array $parameters) {
    return [
        Route::NAME => $faker->unique()->numberBetween(1, 100),
        // Have to be filled outside with valid business-logic value
        Route::COMPANY_ID => $parameters[Route::COMPANY_ID] ?? null,
    ];
});

$factory->define(User::class, function (Generator $faker, array $parameters) {
    return [
        User::FIRST_NAME => $faker->firstName,
        User::LAST_NAME => $faker->lastName,
        User::EMAIL => $faker->safeEmail,
        User::PASSWORD => '123456',
        User::REMEMBER_TOKEN => str_random(10),
        // Have to be filled outside with valid business-logic value
        User::ROLE_ID => $parameters[User::ROLE_ID] ?? null,
        User::COMPANY_ID => $parameters[User::COMPANY_ID] ?? null,
    ];
});

$factory->define(Validator::class, function (Generator $faker, array $parameters) {
    return [
        Validator::MODEL => $faker->companySuffix,
        Validator::SERIAL_NUMBER => $faker->unique()->randomNumber(8, true),
        Validator::EXTERNAL_ID => $faker->unique()->randomNumber(8, true),
        // Have to be filled outside with valid business-logic value
        Validator::BUS_ID => $parameters[Validator::BUS_ID] ?? null,
    ];
});

$factory->define(Replenishment::class, function (Generator $faker, array $parameters) {
    return [
        // Have to be filled outside with valid business-logic value
        Replenishment::CARD_ID => $parameters[Replenishment::CARD_ID],
        Replenishment::REPLENISHED_AT => $parameters[Replenishment::REPLENISHED_AT],
        Replenishment::AMOUNT => $parameters[Replenishment::AMOUNT] ?? round(random_int(100, 5000), -2),
    ];
});
