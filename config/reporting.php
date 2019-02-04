<?php

use Psr\Log\LogLevel;

return [
    'logs' => [
        'enabled' => true,
        'reportedLevels' => [
            LogLevel::EMERGENCY,
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            LogLevel::ERROR,
            LogLevel::WARNING,
            LogLevel::NOTICE,
            LogLevel::INFO,
            LogLevel::DEBUG,
        ],
    ],
    'errors' => [
        'enabled' => true,
    ],
];
