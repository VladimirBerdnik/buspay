<?php

namespace App\Domain\EntitiesServices;

use App\Extensions\EntityService;
use App\Models\Card;
use App\Models\CardType;
use Illuminate\Contracts\Config\Repository as ConfigurationRepository;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Saritasa\Exceptions\ConfigurationException;
use Saritasa\LaravelRepositories\Contracts\IRepository;
use Saritasa\LaravelRepositories\Exceptions\ModelNotFoundException;

/**
 * Card types entity service.
 */
class CardTypeEntityService extends EntityService
{
    /**
     * Configuration storage.
     *
     * @var ConfigurationRepository
     */
    private $configurationRepository;

    /**
     * Card types entity service.
     *
     * @param ConnectionInterface $connection Storage connection interface
     * @param IRepository $repository Handled entities records storage
     * @param ConfigurationRepository $configurationRepository Configuration storage
     */
    public function __construct(
        ConnectionInterface $connection,
        IRepository $repository,
        ConfigurationRepository $configurationRepository
    ) {
        parent::__construct($connection, $repository);
        $this->configurationRepository = $configurationRepository;
    }

    /**
     * Returns whether passed card belongs to passengers cards or not.
     *
     * @param Card $card Card to check
     *
     * @return boolean
     *
     * @throws ConfigurationException
     */
    public function isPassengerCard(Card $card): bool
    {
        return $this->getPassengersCardTypes()->contains(CardType::ID, $card->card_type_id);
    }

    /**
     * Returns whether passed card belongs to driver card or not.
     *
     * @param Card $card Card to check
     *
     * @return boolean
     *
     * @throws ConfigurationException
     */
    public function isDriverCard(Card $card): bool
    {
        return $this->getDriverCardType() === $card->card_type_id;
    }

    /**
     * Returns all passengers cards types.
     *
     * @return Collection
     *
     * @throws ConfigurationException
     */
    public function getPassengersCardTypes(): Collection
    {
        $passengersCardTypesIdentifiers = $this->configurationRepository
                ->get('buspay.passengers.card_types_ids') ?? [];

        if (!$passengersCardTypesIdentifiers) {
            throw new ConfigurationException('No passengers card types configured');
        }

        $passengersCardTypes = new Collection([]);

        foreach ($passengersCardTypesIdentifiers as $passengerCardTypeId) {
            try {
                $passengersCardTypes->push($this->getRepository()->findOrFail($passengerCardTypeId));
            } catch (ModelNotFoundException $exception) {
                throw new ConfigurationException(
                    "Invalid passenger card type identifier provided: {$passengerCardTypeId}"
                );
            }
        }

        return $passengersCardTypes;
    }

    /**
     * Returns base passenger card type.
     *
     * @return CardType
     *
     * @throws ConfigurationException
     */
    public function getBasePassengerCardType(): CardType
    {
        $basePassengerCardTypeId = $this->configurationRepository->get('buspay.passengers.base_card_type_id');

        if (!$basePassengerCardTypeId) {
            throw new ConfigurationException('No base passenger card type configured');
        }

        try {
            /**
             * Initialised passenger card type.
             *
             * @var CardType $basePassengerCardType
             */
            $basePassengerCardType = $this->getRepository()->findOrFail($basePassengerCardTypeId);
        } catch (ModelNotFoundException $exception) {
            throw new ConfigurationException(
                "Invalid base passenger card type identifier provided: {$basePassengerCardTypeId}"
            );
        }

        $passengersCardTypes = $this->getPassengersCardTypes();

        if (!$passengersCardTypes->contains(CardType::ID, $basePassengerCardType->getKey())) {
            throw new ConfigurationException("Base passenger card type doesn't belongs to passengers card types");
        }

        return $basePassengerCardType;
    }

    /**
     * Returns driver card type.
     *
     * @return CardType
     *
     * @throws ConfigurationException
     */
    public function getDriverCardType(): CardType
    {
        $driverCardTypeId = $this->configurationRepository->get('buspay.driver.card_type_id');

        if (!$driverCardTypeId) {
            throw new ConfigurationException('No driver card type configured');
        }

        try {
            /**
             * Initialised driver card type.
             *
             * @var CardType $driverCardType
             */
            $driverCardType = $this->getRepository()->findOrFail($driverCardTypeId);
        } catch (ModelNotFoundException $exception) {
            throw new ConfigurationException("Invalid driver card type identifier provided: {$driverCardTypeId}");
        }

        if ($this->getPassengersCardTypes()->contains(CardType::ID, $driverCardType->getKey())) {
            throw new ConfigurationException("Driver card type should not belongs to passengers card types");
        }

        return $driverCardType;
    }
}
