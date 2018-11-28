<?php

namespace App\Domain\Exceptions\Constraint\CardAuthorization;

use App\Models\Bus;
use App\Models\Driver;

/**
 * Thrown when driver authorized in bus that belongs to another company.
 */
class WrongBusDriverAuthorizationException extends CardAuthorizationException
{
    /**
     * Authorized driver.
     *
     * @var Driver
     */
    private $driver;

    /**
     * Bus where driver was authorized.
     *
     * @var Bus
     */
    private $bus;

    /**
     * Thrown when driver authorized in bus that belongs to another company.
     *
     * @param Driver $driver Authorized driver
     * @param Bus $bus Bus where driver was authorized
     */
    public function __construct(Driver $driver, Bus $bus)
    {
        parent::__construct('Unsupported card authorization');
        $this->driver = $driver;
        $this->bus = $bus;
    }

    /**
     * Authorized driver.
     *
     * @return Driver
     */
    public function getDriver(): Driver
    {
        return $this->driver;
    }

    /**
     * Bus where driver was authorized.
     *
     * @return Bus
     */
    public function getBus(): Bus
    {
        return $this->bus;
    }
}
