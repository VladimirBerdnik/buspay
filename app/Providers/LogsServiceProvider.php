<?php

namespace App\Providers;

use App\Extensions\ErrorsReporter;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Log\Events\MessageLogged;
use Log;

/**
 * Registers listeners for logged messages.
 */
class LogsServiceProvider extends ServiceProvider
{
    /** {@inheritdoc} */
    public function register(): void
    {
        parent::register();

        Log::listen(function (MessageLogged $message): void {
            app()->make(ErrorsReporter::class)->reportMessage($message->message, $message->level, $message->context);
        });
    }
}
