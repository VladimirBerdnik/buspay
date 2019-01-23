<?php

namespace App\Domain\Services;

use App\Domain\EntitiesServices\CardTypeEntityService;
use App\Models\CardType;
use Illuminate\Contracts\Config\Repository as ConfigurationRepository;
use Illuminate\Support\Collection;
use Saritasa\Exceptions\ConfigurationException;
use Saritasa\LaravelRepositories\Exceptions\ModelNotFoundException;

/**
 * Card types business logic service.
 */
class CardTypeService
{
    /**
     * Configuration storage.
     *
     * @var ConfigurationRepository
     */
    private $configurationRepository;

    /**
     * Card types business logic service.
     *
     * @var CardTypeEntityService
     */
    private $cardTypeEntityService;

    /**
     * Card types entity service.
     *
     * @param ConfigurationRepository $configurationRepository Configuration storage
     * @param CardTypeEntityService $cardTypeEntityService Card types entity service
     */
    public function __construct(
        ConfigurationRepository $configurationRepository,
        CardTypeEntityService $cardTypeEntityService
    ) {
        $this->configurationRepository = $configurationRepository;
        $this->cardTypeEntityService = $cardTypeEntityService;
    }

    /**
     * Returns whether given card type identifier valid or not.
     *
     * @param int $cardTypeIdentifier Card type identifier to check
     *
     * @return boolean
     */
    public function isValidCardTypeIdentifier(int $cardTypeIdentifier): bool
    {
        return (bool)$this->cardTypeEntityService->findWhere([CardType::ID => $cardTypeIdentifier]);
    }

    /**
     * Returns whether given card type identifier is ignorable card type.
     *
     * @param int $cardTypeIdentifier Card type identifier to check
     *
     * @return boolean
     */
    public function ignorableCardType(int $cardTypeIdentifier): bool
    {
        $identifiers = $this->configurationRepository->get('buspay.authorization.ignored_card_types_identifiers') ?? [];

        return in_array($cardTypeIdentifier, $identifiers);
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
                ->get('buspay.passenger.card_types_ids') ?? [];

        if (!$passengersCardTypesIdentifiers) {
            throw new ConfigurationException('No passengers card types configured');
        }

        $passengersCardTypes = new Collection([]);

        foreach ($passengersCardTypesIdentifiers as $passengerCardTypeId) {
            try {
                $passengersCardTypes->push($this->cardTypeEntityService->findOrFail($passengerCardTypeId));
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
        $basePassengerCardTypeId = $this->configurationRepository->get('buspay.passenger.base_card_type_id');

        if (!$basePassengerCardTypeId) {
            throw new ConfigurationException('No base passenger card type configured');
        }

        try {
            /**
             * Initialised passenger card type.
             *
             * @var CardType $basePassengerCardType
             */
            $basePassengerCardType = $this->cardTypeEntityService->findOrFail($basePassengerCardTypeId);
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
            $driverCardType = $this->cardTypeEntityService->findOrFail($driverCardTypeId);
        } catch (ModelNotFoundException $exception) {
            throw new ConfigurationException("Invalid driver card type identifier provided: {$driverCardTypeId}");
        }

        if ($this->getPassengersCardTypes()->contains(CardType::ID, $driverCardType->getKey())) {
            throw new ConfigurationException("Driver card type should not belongs to passengers card types");
        }

        return $driverCardType;
    }
}
