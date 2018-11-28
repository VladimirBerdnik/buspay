<?php

namespace App\Console\Commands;

use App\Domain\EntitiesServices\RouteSheetService;
use Illuminate\Console\Command;
use Saritasa\Exceptions\InvalidEnumValueException;
use Saritasa\Exceptions\NotImplementedException;

/**
 * Command to close all opened route sheets.
 */
class CloseRouteSheets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avtotoleu:close_route_sheets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to close all opened route sheets';

    /**
     * Route sheets business logic service.
     *
     * @var RouteSheetService
     */
    private $routeSheetService;

    /**
     * Create a new command instance.
     *
     * @param RouteSheetService $routeSheetService Route sheets business logic service
     */
    public function __construct(RouteSheetService $routeSheetService)
    {
        parent::__construct();
        $this->routeSheetService = $routeSheetService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     *
     * @throws InvalidEnumValueException
     * @throws NotImplementedException
     */
    public function handle(): void
    {
        $this->routeSheetService->closeOpenedRouteSheets();
    }
}
