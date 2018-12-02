<?php

namespace App\Domain\EntitiesServices;

use App\Domain\Dto\RouteSheetData;
use App\Domain\Exceptions\Constraint\CardAuthorization\WrongBusDriverAuthorizationException;
use App\Domain\Exceptions\Integrity\InconsistentRouteSheetStateException;
use App\Domain\Exceptions\Integrity\TooManyBusRouteSheetsForDateException;
use App\Domain\Exceptions\Integrity\TooManyDriverRouteSheetsForDateException;
use App\Extensions\ActivityPeriod\ActivityPeriodAssistant;
use App\Models\Bus;
use App\Models\Driver;
use App\Models\RouteSheet;
use Carbon\Carbon;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Log;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\Exceptions\NotImplementedException;
use Saritasa\LaravelRepositories\DTO\SortOptions;
use Saritasa\LaravelRepositories\Enums\OrderDirections;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;

/**
 * Route sheets service.
 */
class RouteSheetService
{
    /**
     * Route sheets entity service.
     *
     * @var RouteSheetEntityService
     */
    private $routeSheetEntityService;

    /**
     * Configuration storage.
     *
     * @var Repository
     */
    private $config;

    /**
     * Route sheets service.
     *
     * @param RouteSheetEntityService $routeSheetEntityService Route sheets entity service
     * @param Repository $config Configuration storage
     */
    public function __construct(RouteSheetEntityService $routeSheetEntityService, Repository $config)
    {
        $this->routeSheetEntityService = $routeSheetEntityService;
        $this->config = $config;
    }

    /**
     * Returns route sheet for bus at given date and time.
     *
     * @param Bus $bus Bus to retrieve route sheet for
     * @param Carbon|null $date Date to retrieve bus route sheet at
     *
     * @return RouteSheet|null
     *
     * @throws TooManyBusRouteSheetsForDateException
     */
    public function getForBus(Bus $bus, ?Carbon $date): ?RouteSheet
    {
        $date = $date ?? Carbon::now();

        $routeSheets = $this->routeSheetEntityService->getWith(
            [],
            [],
            [
                [RouteSheet::BUS_ID => $bus->id],
                [ActivityPeriodAssistant::ACTIVE_FROM, '<=', $date],
                [
                    [
                        [ActivityPeriodAssistant::ACTIVE_TO, '=', null, 'or'],
                        [ActivityPeriodAssistant::ACTIVE_TO, '>=', $date, 'or'],
                    ],
                ],
            ]
        );

        if ($routeSheets->count() > 1) {
            throw new TooManyBusRouteSheetsForDateException($date, $bus, $routeSheets);
        }

        if ($routeSheets->count() === 1) {
            return $routeSheets->first();
        }

        return null;
    }

    /**
     * Returns route sheet for driver at given date and time.
     *
     * @param Driver $driver Driver to retrieve route sheet for
     * @param Carbon|null $date Date to retrieve driver route sheet at
     *
     * @return RouteSheet|null
     *
     * @throws TooManyDriverRouteSheetsForDateException
     */
    public function getForDriver(Driver $driver, ?Carbon $date): ?RouteSheet
    {
        $date = $date ?? Carbon::now();

        $routeSheets = $this->routeSheetEntityService->getWith(
            [],
            [],
            [
                [RouteSheet::DRIVER_ID => $driver->id],
                [ActivityPeriodAssistant::ACTIVE_FROM, '<=', $date],
                [
                    [
                        [ActivityPeriodAssistant::ACTIVE_TO, '=', null, 'or'],
                        [ActivityPeriodAssistant::ACTIVE_TO, '>=', $date, 'or'],
                    ],
                ],
            ]
        );

        if ($routeSheets->count() > 1) {
            throw new TooManyDriverRouteSheetsForDateException($date, $driver, $routeSheets);
        }

        if ($routeSheets->count() === 1) {
            return $routeSheets->first();
        }

        return null;
    }

    /**
     * Closes route sheet activity period.
     *
     * @param RouteSheet $routeSheet Route sheet to close
     * @param Carbon|null $date Date when route sheet should be closed
     *
     * @throws ValidationException
     * @throws RepositoryException
     */
    public function closeRouteSheet(RouteSheet $routeSheet, ?Carbon $date = null): void
    {
        $date = $date ?? Carbon::now();

        Log::debug("Close route sheet [{$routeSheet->id}] at {$date->toIso8601String()} attempt");

        $routeSheetData = new RouteSheetData([
            RouteSheetData::COMPANY_ID => $routeSheet->company_id,
            RouteSheetData::ROUTE_ID => $routeSheet->route_id,
            RouteSheetData::BUS_ID => $routeSheet->bus_id,
            RouteSheetData::DRIVER_ID => $routeSheet->driver_id,
            RouteSheetData::ACTIVE_FROM => $routeSheet->active_from,
            RouteSheetData::ACTIVE_TO => $date,
        ]);

        $this->routeSheetEntityService->update($routeSheet, $routeSheetData);

        Log::debug("Route sheet [{$routeSheet->id}] closed");
    }

    /**
     * Closes all opened route sheets.
     *
     * @param Carbon|null $date Date to close route sheets at
     *
     * @throws InvalidEnumValueException
     * @throws NotImplementedException
     */
    public function closeOpenedRouteSheets(?Carbon $date = null): void
    {
        $date = $date ?? Carbon::now();

        Log::debug('Close all opened route sheets attempt');

        $closedRouteSheetsCount = 0;

        $this->routeSheetEntityService->chunkWith(
            [],
            [],
            [RouteSheet::ACTIVE_TO => null],
            new SortOptions(RouteSheet::COMPANY_ID),
            100,
            function (Collection $routeSheets) use (&$closedRouteSheetsCount, $date): void {
                foreach ($routeSheets as $routeSheet) {
                    $closedRouteSheetsCount++;
                    $this->closeRouteSheet($routeSheet, $date);
                }
            }
        );

        Log::info("Closed {$closedRouteSheetsCount} opened route sheets");
    }

    /**
     * Returns safety interval in minutes during which second driver authorization on validator will be ignored.
     *
     * @return integer
     */
    public function getRepeatedAuthenticationSafetyMinutesInterval(): int
    {
        return max(0, (int)$this->config->get('buspay.driver.authentication_safe_minutes_interval')) ?? 0;
    }

    /**
     * Validates driver on bus authorization.
     *
     * @param Driver $driver Authorized driver
     * @param Bus $bus Bur where authorization was performed
     */
    protected function validateDriverOnBusAuthorization(Driver $driver, Bus $bus): void
    {
        if ($driver->company_id !== $bus->company_id) {
            throw new WrongBusDriverAuthorizationException($driver, $bus);
        }
    }

    /**
     * Opens route sheet for given bus and driver.
     *
     * @param Bus $bus Bus to open route sheet for
     * @param Driver $driver Driver to assign to route sheet on bus
     * @param Carbon|null $date Date to open route sheet at
     *
     * @return void
     *
     * @throws RepositoryException
     * @throws TooManyBusRouteSheetsForDateException
     * @throws ValidationException
     * @throws InconsistentRouteSheetStateException
     * @throws InvalidEnumValueException
     * @throws TooManyDriverRouteSheetsForDateException
     */
    public function openForBusAndDriver(Bus $bus, Driver $driver, ?Carbon $date = null): void
    {
        $date = $date ?? Carbon::now();

        if ($driver) {
            $this->validateDriverOnBusAuthorization($driver, $bus);
            $driverRouteSheet = $this->getForDriver($driver, $date);
        } else {
            $driverRouteSheet = null;
        }

        $safetyMinutesInterval = $this->getRepeatedAuthenticationSafetyMinutesInterval();
        $safeRepeatedAuthorizationTime = Carbon::now()->subMinutes($safetyMinutesInterval);

        if ($driverRouteSheet) {
            $anotherBusAuthorization = $driverRouteSheet->bus_id !== $bus->id;
            $safetyRepeatedIntervalExceeded = $driverRouteSheet->active_from->lt($safeRepeatedAuthorizationTime);
            if ($anotherBusAuthorization) {
                Log::debug("Driver [{$driver->id}] authorized on new bus [{$bus->id}]. Closing current route sheet");
                $this->closeRouteSheet($driverRouteSheet, $date->copy()->subSecond());
                $driverRouteSheet = null;
            } elseif ($safetyRepeatedIntervalExceeded) {
                Log::debug("Driver [{$driver->id}] authorized on bus [{$bus->id}] again. Recreating route sheet");
                $this->closeRouteSheet($driverRouteSheet, $date->copy()->subSecond());
                $driverRouteSheet = null;
            } else {
                Log::debug("Driver [{$driver->id}] authorized on bus [{$bus->id}] again in safety interval. Ignoring");
            }
            // If driver route sheet still exists it means that allowed repeated authorization on same bus was performed
        }

        $busRouteSheet = $this->getForBus($bus, $date);
        if ($busRouteSheet) {
            $anotherDriverAuthorization = $driver && $busRouteSheet->driver_id !== $driver->id;
            $safetyRepeatedIntervalExceeded = $busRouteSheet->active_from->lt($safeRepeatedAuthorizationTime);
            if (!$driver) {
                Log::debug("Route sheet [{$busRouteSheet->id}] for bus exists but no new driver provided");
            } elseif (!$busRouteSheet->driver_id) {
                Log::debug("Route sheet [{$busRouteSheet->id}] for bus without driver found. Assigning given driver");

                $this->routeSheetEntityService->assignDriver($busRouteSheet, $driver);

                return;
            } elseif ($anotherDriverAuthorization) {
                Log::debug("Another driver [{$driver->id}] have worked on bus [{$bus->id}]. Recreating route sheet");
                $this->closeRouteSheet($busRouteSheet, $date->copy()->subSecond());
                $busRouteSheet = null;
            } elseif ($safetyRepeatedIntervalExceeded) {
                Log::error("Route sheet [$busRouteSheet->id] should be closed as it is the same as driver route sheet");

                throw new InconsistentRouteSheetStateException();
            }
            // If driver route sheet still exists it means that allowed repeated authorization on same bus was performed
        }

        if ($busRouteSheet && $driverRouteSheet && $busRouteSheet->id !== $driverRouteSheet->id) {
            Log::error("One of route sheets [{$busRouteSheet->id}, {$driverRouteSheet->id}] should be closed");

            throw new InconsistentRouteSheetStateException();
        }

        if ($busRouteSheet || !$driverRouteSheet) {
            return;
        }

        /**
         * Route sheet which date is greater than this date. Need to make this route sheet with closed date.
         *
         * @var RouteSheet $routeSheetAfter
         */
        $routeSheetAfter = $this->routeSheetEntityService
            ->getWith(
                [],
                [],
                [
                    RouteSheet::BUS_ID => $bus->id,
                    [RouteSheet::ACTIVE_FROM, '>', $date],
                ],
                new SortOptions(RouteSheet::ACTIVE_FROM, OrderDirections::DESC)
            )
            ->first();

        $routeSheetData = new RouteSheetData([
            RouteSheetData::COMPANY_ID => $bus->company_id,
            RouteSheetData::ROUTE_ID => $bus->route_id,
            RouteSheetData::BUS_ID => $bus->id,
            RouteSheetData::DRIVER_ID => $driver ? $driver->id : null,
            RouteSheetData::ACTIVE_FROM => $date,
            RouteSheetData::ACTIVE_TO => $routeSheetAfter
                ? $routeSheetAfter->active_from->copy()->subSecond()
                : $date->copy()->endOfDay(),
        ]);

        $this->routeSheetEntityService->store($routeSheetData);
    }
}
