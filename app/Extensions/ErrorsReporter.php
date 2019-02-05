<?php

namespace App\Extensions;

use App\Domain\Exceptions\Constraint\BusinessLogicConstraintException;
use App\Domain\Exceptions\Integrity\BusinessLogicIntegrityException;
use App\Notifications\LogNotification;
use Illuminate\Validation\ValidationException;
use Log;
use Psr\Log\LogLevel;
use Throwable;

/**
 * Reports about exceptions and logged messages.
 */
class ErrorsReporter
{
    /**
     * Notifiable slack reporting channel.
     *
     * @var SlackReportingChannel
     */
    private $slackReportingChannel;

    /**
     * Reports about exceptions and logged messages.
     *
     * @param SlackReportingChannel $slackReportingChannel Notifiable slack reporting channel
     */
    public function __construct(SlackReportingChannel $slackReportingChannel)
    {
        $this->slackReportingChannel = $slackReportingChannel;
    }

    /**
     * Reports about passed exception.
     *
     * @param Throwable $exception Exception that should be reported
     * @param mixed[] $context Additional context of exception
     */
    public function reportException(Throwable $exception, array $context = []): void
    {
        if (!config('reporting.errors.enabled')) {
            return;
        }

        $message = $exception->getMessage();
        $severity = LogLevel::ERROR;
        try {
            switch (true) {
                case $exception instanceof ValidationException:
                    $data = $exception->errors();
                    $severity = LogLevel::DEBUG;
                    break;
                case $exception instanceof BusinessLogicConstraintException:
                    $data = $exception->getContext();
                    $message = $exception->__toString();
                    break;
                case $exception instanceof BusinessLogicIntegrityException:
                    $data = $exception->getContext();
                    $message = $exception->__toString();
                    $severity = LogLevel::CRITICAL;
                    break;
                default:
                    $data = [
                        "{$exception->getFile()}:{$exception->getLine()}",
                    ];
            }
        } catch (Throwable $e) {
            $data = [get_class($e)];
        }

        Log::log($severity, $message, array_merge($context, $data));
    }

    /**
     * Reports about logged message.
     *
     * @param string $message Message that was logged
     * @param string $severity Severity of message
     * @param mixed[]|null $data Additional data, related with message
     *
     * @return void
     */
    public function reportMessage(string $message, string $severity, ?array $data = null): void
    {
        if (!config('reporting.logs.enabled')) {
            return;
        }

        $reportableSeverityLevels = config('reporting.logs.reportedLevels') ?? [];
        if (!in_array($severity, $reportableSeverityLevels)) {
            return;
        }

        $sanitizedData = [];

        foreach ($data ?? [] as $key => $value) {
            try {
                $value = is_scalar($value) ? $value : json_encode($value);
            } catch (Throwable $exception) {
                $value = gettype($value);
            }
            $sanitizedData[$key] = $value;
        }

        $this->slackReportingChannel->notify(new LogNotification($message, $severity, $data));
    }
}
