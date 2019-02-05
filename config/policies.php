<?php

use App\Domain\Enums\Abilities;
use App\Domain\Enums\EntitiesTypes;
use App\Domain\Enums\RolesIdentifiers;

/**
 * List of allowed for roles actions. Every entity has it's own list of possible actions.
 * Administrator can do everything that's why he is not presented in list of roles for intentions.
 */
return [
    EntitiesTypes::BUS => [
        Abilities::GET => [RolesIdentifiers::OPERATOR, RolesIdentifiers::SUPPORT],
        Abilities::SHOW => [RolesIdentifiers::OPERATOR, RolesIdentifiers::SUPPORT],
        Abilities::CREATE => [RolesIdentifiers::SUPPORT],
        Abilities::UPDATE => [RolesIdentifiers::SUPPORT],
        Abilities::CHANGE_BUS_ROUTE => [RolesIdentifiers::OPERATOR, RolesIdentifiers::SUPPORT],
        Abilities::DELETE => [],
    ],
    EntitiesTypes::CARD_TYPE => [
        Abilities::GET => [RolesIdentifiers::SUPPORT],
    ],
    EntitiesTypes::CARD => [
        Abilities::GET => [RolesIdentifiers::SUPPORT],
        Abilities::GET_DRIVERS_CARDS => [RolesIdentifiers::SUPPORT],
    ],
    EntitiesTypes::COMPANY => [
        Abilities::GET => [RolesIdentifiers::OPERATOR, RolesIdentifiers::SUPPORT],
        Abilities::SHOW => [RolesIdentifiers::OPERATOR, RolesIdentifiers::SUPPORT],
        Abilities::CREATE => [],
        Abilities::UPDATE => [],
        Abilities::DELETE => [],
    ],
    EntitiesTypes::DRIVER => [
        Abilities::GET => [RolesIdentifiers::OPERATOR, RolesIdentifiers::SUPPORT],
        Abilities::SHOW => [RolesIdentifiers::OPERATOR, RolesIdentifiers::SUPPORT],
        Abilities::CREATE => [RolesIdentifiers::SUPPORT],
        Abilities::UPDATE => [RolesIdentifiers::SUPPORT],
        Abilities::CHANGE_DRIVER_BUS => [RolesIdentifiers::OPERATOR, RolesIdentifiers::SUPPORT],
        Abilities::DELETE => [],
    ],
    EntitiesTypes::PROFILE => [
        Abilities::SHOW => [RolesIdentifiers::OPERATOR, RolesIdentifiers::SUPPORT],
        Abilities::UPDATE => [RolesIdentifiers::OPERATOR, RolesIdentifiers::SUPPORT],
    ],
    EntitiesTypes::REPLENISHMENT => [
        Abilities::GET => [RolesIdentifiers::SUPPORT],
    ],
    EntitiesTypes::ROLE => [
        Abilities::GET => [RolesIdentifiers::SUPPORT],
    ],
    EntitiesTypes::ROUTE_SHEET => [
        Abilities::GET => [RolesIdentifiers::OPERATOR, RolesIdentifiers::SUPPORT],
        Abilities::SHOW => [RolesIdentifiers::OPERATOR, RolesIdentifiers::SUPPORT],
        Abilities::CREATE => [RolesIdentifiers::OPERATOR],
        Abilities::UPDATE => [RolesIdentifiers::OPERATOR],
        Abilities::DELETE => [RolesIdentifiers::OPERATOR],
    ],
    EntitiesTypes::ROUTE => [
        Abilities::GET => [RolesIdentifiers::OPERATOR, RolesIdentifiers::SUPPORT],
        Abilities::SHOW => [RolesIdentifiers::OPERATOR, RolesIdentifiers::SUPPORT],
        Abilities::CREATE => [],
        Abilities::UPDATE => [],
        Abilities::DELETE => [],
    ],
    EntitiesTypes::TARIFF => [
        Abilities::GET => [RolesIdentifiers::SUPPORT],
        Abilities::SHOW => [RolesIdentifiers::SUPPORT],
        Abilities::CREATE => [],
        Abilities::UPDATE => [],
        Abilities::DELETE => [],
    ],
    EntitiesTypes::TARIFF_PERIOD => [
        Abilities::GET => [RolesIdentifiers::SUPPORT],
        Abilities::SHOW => [RolesIdentifiers::SUPPORT],
        Abilities::CREATE => [],
        Abilities::UPDATE => [],
        Abilities::DELETE => [],
    ],
    EntitiesTypes::TRANSACTION => [
        Abilities::GET => [RolesIdentifiers::SUPPORT],
    ],
    EntitiesTypes::USER => [
        Abilities::GET => [RolesIdentifiers::SUPPORT],
        Abilities::SHOW => [RolesIdentifiers::SUPPORT],
        Abilities::CREATE => [],
        Abilities::UPDATE => [],
        Abilities::DELETE => [],
    ],
    EntitiesTypes::VALIDATOR => [
        Abilities::GET => [RolesIdentifiers::SUPPORT],
        Abilities::SHOW => [RolesIdentifiers::SUPPORT],
        Abilities::UPDATE => [RolesIdentifiers::SUPPORT],
    ],
];
