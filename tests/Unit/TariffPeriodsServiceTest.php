<?php

namespace Tests\Unit;

use App\Domain\Exceptions\Integrity\NoTariffPeriodForDateException;
use App\Domain\Exceptions\Integrity\TooManyTariffPeriodsForDateException;
use App\Domain\Services\TariffPeriodService;
use App\Models\TariffPeriod;
use App\Repositories\TariffPeriodRepository;
use Carbon\Carbon;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class TariffPeriodsServiceTest extends TestCase
{
    /**
     * Test that few tariff periods for date should not be exists.
     *
     * @return void
     *
     * @throws TooManyTariffPeriodsForDateException
     */
    public function testExceptionWhenMultipleForDate(): void
    {
        $tariffPeriodsRepository = Mockery::mock(TariffPeriodRepository::class);

        $tariffPeriods = new Collection([new TariffPeriod(), new TariffPeriod()]);
        $tariffPeriodsRepository->shouldReceive('getWith')->andReturn($tariffPeriods);

        $tariffPeriodsService = new TariffPeriodService(app(ConnectionInterface::class), $tariffPeriodsRepository);

        $this->expectException(TooManyTariffPeriodsForDateException::class);

        $tariffPeriodsService->getForDate(Carbon::now());
    }

    /**
     * Test that for now tariff should exists.
     *
     * @return void
     *
     * @throws TooManyTariffPeriodsForDateException
     * @throws NoTariffPeriodForDateException
     */
    public function testExceptionWhenNoForNow(): void
    {
        $tariffPeriodsRepository = Mockery::mock(TariffPeriodRepository::class);

        $tariffPeriods = new Collection([]);
        $tariffPeriodsRepository->shouldReceive('getWith')->andReturn($tariffPeriods);

        $tariffPeriodsService = new TariffPeriodService(app(ConnectionInterface::class), $tariffPeriodsRepository);

        $this->expectException(NoTariffPeriodForDateException::class);

        $tariffPeriodsService->getCurrent();
    }
}
