<?php

use Psr\Log\LogLevel;

return [
    'dsn' => env('SENTRY_LARAVEL_DSN'),

    // capture release as git sha
    'release' => trim(exec('git log --pretty="%h" -n1 HEAD')),

    // Capture bindings on SQL queries
    'breadcrumbs.sql_bindings' => true,

    // Capture default user context
    'user_context' => true,

    'enabled' => env('SENTRY_ENABLED'),

    // Messages of this levels will be captured by Sentry
    'capturable_log_levels' => [
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
        LogLevel::WARNING,
        LogLevel::NOTICE,
        LogLevel::INFO,
    ],
];
