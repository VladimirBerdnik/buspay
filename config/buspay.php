<?php

use App\Domain\Enums\CardTypesIdentifiers;

return [
    /**
     * Fare configuration.
     */
    'fare' => [
        // Base payment card type identifier
        'base_card_type_id' => CardTypesIdentifiers::DEFAULT,

        // Preferential card types identifiers, used to determine compensation amount to transport companies
        'preferential_card_types' => [
            CardTypesIdentifiers::CHILD,
            CardTypesIdentifiers::RETIRE,
            CardTypesIdentifiers::FREE,
        ],
    ],
    /**
     * Driver related configuration.
     */
    'driver' => [
        // Drivers card type identifier
        'card_type_id' => CardTypesIdentifiers::DRIVER,

        // Hour at which driver's shift on bus should be automatically cancelled (0-23)
        'shift_cancel_hour' => 5,
    ],
];
