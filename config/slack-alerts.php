<?php

return [
    /*
     * The webhook URLs that we'll use to send a message to Slack.
     */
    'webhook_urls' => [
        'default' => env('SLACK_WEBHOOK_URL'),
        'error-logs' => env('SLACK_ERROR_WEBHOOK'),
    ],

    /*
     * This job will send the message to Slack. You can extend this
     * job to set timeouts, retries, etc...
     */
    'job' => \AIGenerate\Services\Exceptions\Notifications\SpatieSlackNotification::class,
];
