<?php

namespace App\Domain\Exceptions\Integrity;

use App\Models\Driver;

/**
 * Thrown when card to driver assignment not found, but expected.
 */
class NoDriverForCardException extends BusinessLogicIntegrityException
{
    /**
     * Driver for which driver card not found.
     *
     * @var Driver
     */
    private $driver;

    /**
     * Thrown when card to driver assignment not found, but expected.
     *
     * @param Driver $driver Driver for which driver card not found
     */
    public function __construct(Driver $driver)
    {
        parent::__construct('No card to driver historical assignment found when expected');
        $this->driver = $driver;
    }

    /**
     * Driver for which driver card not found.
     *
     * @return Driver
     */
    public function getDriver(): Driver
    {
        return $this->driver;
    }

    /**
     * Text representation of exception.
     *
     * @return string
     */
    public function __toString(): string
    {
        $driver = $this->getDriver();

        return "No driver [{$driver->id}] to card [{$driver->card_id}] historical assignment found, but expected";
    }
}
