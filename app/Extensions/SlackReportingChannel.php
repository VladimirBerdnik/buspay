<?php

namespace App\Extensions;

use Illuminate\Notifications\Notifiable;

/**
 * Notifiable slack reporting channel.
 */
class SlackReportingChannel
{
    use Notifiable;

    /**
     * Route notifications for the Slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack(): string
    {
        return config('reporting.services.slack.url');
    }
}
