<?php

/**
 * External data import configuration.
 */

return [
    'card' => [
        'importChunkSize' => 50,
    ],
    'validator' => [
        'importChunkSize' => 50,
    ],
    'replenishment' => [
        'importChunkSize' => 50,
        // Replenishment records within given days interval will be checked for presence in local and external storage
        'synchronisationDaysInterval' => 14,
        // Suspicious replenishment amount. If replenishment will be greater than given value then notice will be logged
        'suspiciousAmountLimit' => 10000,
    ],
];
