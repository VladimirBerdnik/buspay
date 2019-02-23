<?php

namespace App\Domain\EntitiesServices;

use App\Domain\Dto\RouteSheetData;
use App\Domain\Exceptions\Constraint\RouteSheetDeletionException;
use App\Extensions\ActivityPeriod\ActivityPeriodFilterer;
use App\Extensions\EntityService;
use App\Models\Bus;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Route;
use App\Models\RouteSheet;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule as IlluminateRule;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\Laravel\Validation\DateRuleSet;
use Saritasa\Laravel\Validation\GenericRuleSet;
use Saritasa\Laravel\Validation\Rule;
use Saritasa\Laravel\Validation\RuleSet;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Validator;

/**
 * Route sheet entity service.
 */
class RouteSheetEntityService extends EntityService
{
    use ActivityPeriodFilterer;

    /**
     * Returns validation rule to store or update route sheet.
     *
     * @param RouteSheet $routeSheet Route sheet to build rules for
     *
     * @return string[]|GenericRuleSet[]
     */
    protected function getRouteSheetValidationRules(RouteSheet $routeSheet): array
    {
        return [
            RouteSheet::COMPANY_ID => Rule::required()->exists('companies', Company::ID),
            RouteSheet::ACTIVE_FROM => Rule::required()->date()
                ->when($routeSheet->getActiveTo(), function (RuleSet $rule) {
                    /**
                     * Date rules set.
                     *
                     * @var DateRuleSet $rule
                     */
                    return $rule->before(RouteSheet::ACTIVE_TO);
                }),
            RouteSheet::ACTIVE_TO => Rule::required()->date()
                ->when($routeSheet->getActiveTo(), function (RuleSet $rule) {
                    /**
                     * Date rules set.
                     *
                     * @var DateRuleSet $rule
                     */
                    return $rule->after(RouteSheet::ACTIVE_FROM);
                }),
            RouteSheet::ROUTE_ID => Rule::nullable()
                // Route should be assigned to company
                ->exists('routes', Route::ID, function (Exists $rule) use ($routeSheet) {
                    $rule->where(Route::COMPANY_ID, $routeSheet->company_id);

                    return $rule->whereNull(Route::DELETED_AT);
                }),
            RouteSheet::BUS_ID => [
                /**
                 * This rule contains Illuminate rules and Saritasa rules due to unique rule issue in Saritasa package.
                 *
                 * @see https://github.com/Saritasa/php-laravel-fluent-validation/issues/13
                 */
                Rule::required()
                    // Bus should be in the same company
                    ->exists('buses', Bus::ID, function (Exists $rule) use ($routeSheet) {
                        $rule->where(Bus::COMPANY_ID, $routeSheet->company_id);

                        return $rule->whereNull(Bus::DELETED_AT);
                    })
                    ->toArray(),

                // Bus should be unique at the same time in route sheets
                IlluminateRule::unique('route_sheets', RouteSheet::BUS_ID)
                    ->using(function (Builder $builder) use ($routeSheet) {
                        if ($routeSheet->exists) {
                            $builder->where(RouteSheet::ID, '!=', $routeSheet->id);
                        }

                        $builder->whereNull(RouteSheet::DELETED_AT);

                        return $this->handleActivityPeriodUniqueness(
                            $builder,
                            $routeSheet->active_from,
                            $routeSheet->active_to
                        );
                    }),
            ],
            RouteSheet::DRIVER_ID => [
                /**
                 * This rule contains Illuminate rules and Saritasa rules due to unique rule issue in Saritasa package.
                 *
                 * @see https://github.com/Saritasa/php-laravel-fluent-validation/issues/13
                 */
                Rule::nullable()
                    // Driver should work in company
                    ->exists('drivers', Driver::ID, function (Exists $rule) use ($routeSheet) {
                        $rule->where(Driver::COMPANY_ID, $routeSheet->company_id);

                        return $rule->whereNull(Driver::DELETED_AT);
                    })
                    ->toArray(),
                // Driver should be unique at the same time in route sheets
                IlluminateRule::unique('route_sheets', RouteSheet::DRIVER_ID)
                    ->using(function (Builder $builder) use ($routeSheet) {
                        if ($routeSheet->exists) {
                            $builder->where(RouteSheet::ID, '!=', $routeSheet->id);
                        }

                        $builder->whereNull(RouteSheet::DELETED_AT);

                        return $this->handleActivityPeriodUniqueness(
                            $builder,
                            $routeSheet->active_from,
                            $routeSheet->active_to
                        );
                    }),
            ],
        ];
    }

    /**
     * Returns customized validation error messages as validation rules are very complex and need to explain exact
     * reason.
     *
     * @return string[]
     */
    private function validationMessages(): array
    {
        return [
            RouteSheet::DRIVER_ID . '.unique' => trans('logicValidation.routeSheet.driver.unique'),
            RouteSheet::BUS_ID . '.unique' => trans('logicValidation.routeSheet.bus.unique'),
        ];
    }

    /**
     * Stores new route sheet.
     *
     * @param RouteSheetData $routeSheetData Route sheet details to create
     *
     * @return RouteSheet
     *
     * @throws RepositoryException
     * @throws ValidationException
     */
    public function store(RouteSheetData $routeSheetData): RouteSheet
    {
        Log::debug("Create route sheet attempt", $routeSheetData->toArray());

        $routeSheet = new RouteSheet($routeSheetData->toArray());

        Validator::validate(
            $routeSheet->toArray(),
            $this->getRouteSheetValidationRules($routeSheet),
            $this->validationMessages()
        );

        $this->getRepository()->create($routeSheet);

        Log::debug("Route sheet [{$routeSheet->id}] created");

        return $routeSheet;
    }

    /**
     * Updates route sheet details.
     *
     * @param RouteSheet $routeSheet Route sheet to update
     * @param RouteSheetData $routeSheetData Route sheet new details
     *
     * @return RouteSheet
     *
     * @throws RepositoryException
     * @throws ValidationException
     */
    public function update(RouteSheet $routeSheet, RouteSheetData $routeSheetData): RouteSheet
    {
        Log::debug("Update route sheet [{$routeSheet->id}] attempt");

        $routeSheet->fill($routeSheetData->toArray());

        Validator::validate(
            $routeSheet->toArray(),
            $this->getRouteSheetValidationRules($routeSheet),
            $this->validationMessages()
        );

        $this->getRepository()->save($routeSheet);

        Log::debug("Route sheet [{$routeSheet->id}] updated");

        return $routeSheet;
    }

    /**
     * Deletes route sheet.
     *
     * @param RouteSheet $routeSheet Route sheet to delete
     *
     * @throws RepositoryException
     */
    public function destroy(RouteSheet $routeSheet): void
    {
        Log::debug("Delete route sheet [{$routeSheet->id}] attempt");

        if ($routeSheet->transactions->isNotEmpty()) {
            throw new RouteSheetDeletionException($routeSheet);
        }

        $this->getRepository()->delete($routeSheet);

        Log::debug("Route sheet [{$routeSheet->id}] deleted");
    }

    /**
     * Assigns driver to route sheet.
     *
     * @param RouteSheet $routeSheet Route sheet to assign driver to
     * @param Driver $driver New driver to assign
     *
     * @return RouteSheet
     *
     * @throws RepositoryException
     * @throws ValidationException
     */
    public function assignDriver(RouteSheet $routeSheet, Driver $driver): RouteSheet
    {
        Log::debug(
            "Assign driver [{$driver->id}] (old: [{$routeSheet->driver_id}]) to route sheet [{$routeSheet->id}] attempt"
        );

        $routeSheet->driver_id = $driver->id;

        Validator::validate(
            $routeSheet->toArray(),
            $this->getRouteSheetValidationRules($routeSheet),
            $this->validationMessages()
        );

        $this->getRepository()->save($routeSheet);

        Log::debug("Driver [{$driver->id}] assigned to route sheet [{$routeSheet->id}]");

        return $routeSheet;
    }
}
