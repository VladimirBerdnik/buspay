<?php

use App\Domain\Enums\CardTypesIdentifiers;

return [
    /**
     * Passenger related configuration.
     */
    'passenger' => [
        // All card types that should be considered as passengers card types
        'card_types_ids' => [
            CardTypesIdentifiers::DEFAULT,
            CardTypesIdentifiers::CHILD,
            CardTypesIdentifiers::RETIRE,
            CardTypesIdentifiers::FREE,
        ],
        // Base passenger payment card type identifier
        'base_card_type_id' => CardTypesIdentifiers::DEFAULT,
    ],
    /**
     * Driver related configuration.
     */
    'driver' => [
        // Drivers card type identifier
        'card_type_id' => CardTypesIdentifiers::DRIVER,

        // Hour at which driver's shift on bus should be automatically cancelled (0-23). Server timezone is used
        'shift_cancel_hour' => '06:00',

        // Interval in minutes during which existing driver bus route sheet will not be closed and opened again
        'authentication_safe_minutes_interval' => 10,
    ],
    /**
     * Card on validator authorization related configuration.
     */
    'authorization' => [
        // Card types identifiers that should be ignored during authorization attempt. Possible system card types
        'ignored_card_types_identifiers' => [],
    ],
];
