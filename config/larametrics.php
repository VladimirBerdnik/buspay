<?php

use App\Models\Bus;
use App\Models\BusesValidator;
use App\Models\Card;
use App\Models\CardType;
use App\Models\CompaniesRoute;
use App\Models\Company;
use App\Models\Driver;
use App\Models\DriversCard;
use App\Models\Role;
use App\Models\Route;
use App\Models\RouteSheet;
use App\Models\Tariff;
use App\Models\TariffFare;
use App\Models\TariffPeriod;
use App\Models\User;
use App\Models\Validator;

return [

    /*
    |--------------------------------------------------------------------------
    | Models Watched
    |--------------------------------------------------------------------------
    |
    | Any app model present in this array is watched by Larametrics, and its
    | changes are stored in the database under the larametrics_models table.
    | If you would like no models watched, change this to an empty array.
    |
    */
    'modelsWatched' => [
        Bus::class,
        BusesValidator::class,
        Card::class,
        CardType::class,
        CompaniesRoute::class,
        Company::class,
        Driver::class,
        DriversCard::class,
        Role::class,
        Route::class,
        RouteSheet::class,
        Tariff::class,
        TariffFare::class,
        TariffPeriod::class,
        User::class,
        Validator::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Models Watched - Expire Days
    |--------------------------------------------------------------------------
    |
    | By default, model changes are stored in the database for 30 days. You
    | can set your own expiration time (in days) here. During each time a change
    | is added to the database, any records older than the expiration time will
    | be removed.
    |
    */
    'modelsWatchedExpireDays' => 15,

    /*
    |--------------------------------------------------------------------------
    | Models Watched - Expire Amount
    |--------------------------------------------------------------------------
    |
    | By default, a max of 1200 model changes are stored in the database at
    | any given time. Set this number to 0 if you would like to have no limit.
    |
    */
    'modelsWatchedExpireAmount' => 600,

    /*
    |--------------------------------------------------------------------------
    | Requests Watched
    |--------------------------------------------------------------------------
    |
    | By default, requests are not watched due to resource conservation. By
    | enabling this portion of the app, all requests to your site and their
    | details will be logged in the larametrics_requests table.
    |
    */
    'requestsWatched' => true,

    /*
    |--------------------------------------------------------------------------
    | Requests To Skip
    |--------------------------------------------------------------------------
    |
    | By default, when requestsWatched is enabled, all requests are  watched.
    | If you would like Larametrics to skip a path you can add it below.
    |
    */
    'requestsToSkip' => [],

    /*
    |--------------------------------------------------------------------------
    | Requests Watched - Expire Days
    |--------------------------------------------------------------------------
    |
    | By default, requests are stored for 10 days after creation. You can set
    | your own expiration time (in days) here. During each request log
    | creation, any items older than the expiration time will be removed.
    |
    */
    'requestsWatchedExpireDays' => 10,

    /*
    |--------------------------------------------------------------------------
    | Requests Watched - Expire Amount
    |--------------------------------------------------------------------------
    |
    | By default, a max of 10000 requests are stored in the database at any
    | given time. Set this number to 0 if you would like to have no limit.
    |
    */
    'requestsWatchedExpireAmount' => 5000,

    /*
    |--------------------------------------------------------------------------
    | Ignore Larametrics Requests
    |--------------------------------------------------------------------------
    |
    | If both this and requestsWatched are both set to true, Larametrics
    | will not log requests that are part of the package.
    |
    */
    'ignoreLarametricsRequests' => true,

    /*
    |--------------------------------------------------------------------------
    | Logs Watched
    |--------------------------------------------------------------------------
    |
    | Any time that the Laravel log is written to, its contents are stored
    | in the database under the larametrics_logs table.
    | If you would like to disable log storage, set this to false.
    |
    */
    'logsWatched' => false,

    /*
    |--------------------------------------------------------------------------
    | Logs Watched - Expire Days
    |--------------------------------------------------------------------------
    |
    | By default, logs  are stored in the database for an unlimited amount
    | of time. You can set your own expiration time (in days) here. Each
    | time a log is added to the database, any records older than the
    | expiration time will be removed.
    |
    */
    'logsWatchedExpireDays' => 1,

    /*
    |--------------------------------------------------------------------------
    | Logs Watched - Expire Amount
    |--------------------------------------------------------------------------
    |
    | By default, there is no limit on logs stored in the database at any
    | given time. Change this number if you would prefer there to be a limit.
    |
    */
    'logsWatchedExpireAmount' => 10,

    /*
    |--------------------------------------------------------------------------
    | Hide Unwatched Menu Items
    |--------------------------------------------------------------------------
    |
    | If this is set to true, the main navigation for Larametrics will hide
    | the links for items (models, requests, logs) that are not being watched.
    */
    'hideUnwatchedMenuItems' => true,

    /*
    |--------------------------------------------------------------------------
    | Notification Methods
    |--------------------------------------------------------------------------
    |
    | Set how you want to be notified here, by default both are env variables
    | but if you'd like to directly override them, you can do so here.
    |
    | 'email' should be a standard email address
    | 'slack' should be a Slack Webhook URL ready to receive messages
    */
    'notificationMethods' => [
        'email' => env('LARAMETRICS_NOTIFICATION_EMAIL'),
    ],

];
