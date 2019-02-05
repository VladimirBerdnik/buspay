<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Psr\Log\LogLevel;

/**
 * Notification about logged message.
 */
class LogNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Message that was logged.
     *
     * @var string
     */
    private $message;

    /**
     * Severity of message.
     *
     * @var string
     */
    private $severity;

    /**
     * Additional data, related with message.
     *
     * @var array|mixed[]|null
     */
    private $data;

    /**
     * Notification about logged message.
     *
     * @param string $message Message that was logged
     * @param string $severity Severity of message
     * @param mixed[]|null $data Additional data, related with message
     */
    public function __construct(string $message, string $severity, ?array $data = null)
    {
        $this->message = $message;
        $this->severity = $severity;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return string[]
     */
    public function via(): array
    {
        return ['slack'];
    }

    /**
     * Get the slack representation of the notification.
     */
    public function toSlack(): SlackMessage
    {
        $message = new SlackMessage();

        switch ($this->severity) {
            case LogLevel::ERROR:
            case LogLevel::CRITICAL:
            case LogLevel::ALERT:
            case LogLevel::EMERGENCY:
                $message->error();
                $sign = ':exclamation:';
                break;
            case LogLevel::WARNING:
            case LogLevel::NOTICE:
                $message->warning();
                $sign = ':warning:';
                break;
            case LogLevel::DEBUG:
                $message->info();
                $sign = ':gear:';
                break;
            default:
                $message->info();
                $sign = ':information_source:';
        }

        $environment = app()->environment();

        $message
            ->to(config('reporting.services.slack.channel'))
            ->image('http://buspay-web.service.docker/images/favicon.png')
            ->content("[{$environment}] {$sign} $this->message");

        if ($this->data) {
            $message->attachment(function (SlackAttachment $attachment): void {
                $attachment
                    ->title('Context')
                    ->fields($this->data);
            });
        }

        return $message;
    }
}
