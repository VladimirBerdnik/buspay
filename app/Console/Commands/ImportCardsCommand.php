<?php

namespace App\Console\Commands;

use App\Domain\Import\CardsImporter;
use Illuminate\Console\Command;
use Throwable;

/**
 * Console command to start cards from external storage import process.
 */
class ImportCardsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avtotoleu:import_cards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Performs import of cards from external storage';

    /**
     * External storage cards importer service.
     *
     * @var CardsImporter
     */
    private $cardsImporter;

    /**
     * Create a new command instance.
     *
     * @param CardsImporter $cardsImporter External storage cards importer service
     */
    public function __construct(CardsImporter $cardsImporter)
    {
        parent::__construct();
        $this->cardsImporter = $cardsImporter;
    }

    /**
     * Execute the console command.
     *
     * @return void
     *
     * @throws Throwable
     */
    public function handle(): void
    {
        $this->cardsImporter->import();
    }
}
