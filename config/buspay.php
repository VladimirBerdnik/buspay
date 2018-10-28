<?php

return [
    /**
     * Fare configuration.
     */
    'fare' => [
        // Base payment card type slug
        'base_card_type_slug' => null,

        // Preferential card types slugs, used to determine compensation amount to transport companies
        'preferential_card_types' => [],
    ],
    /**
     * Driver related configuration.
     */
    'driver' => [
        // Drivers card type identifier
        'card_type_slug' => null,

        // Hour at which driver's shift on bus should be automatically cancelled (0-23)
        'shift_cancel_hour' => 5,
    ],
];
