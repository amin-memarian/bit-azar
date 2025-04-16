<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        'App\Events\UploadPhoto' => [
            'App\Handlers\Events\HandleUploadProfile',
        ],
        'App\Events\pay_commision' => [
            'App\Handlers\Events\handle_pay_commision',
        ],
        'App\Events\Scoring' => [
            'App\Handlers\Events\HandleScoring',
        ],
        'App\Events\OrderVolumenScore' => [
            'App\Handlers\Events\HandleVolumeScore'
        ],
        'App\Events\UserWalletUpdated' => [
            'App\Listeners\ClearWalletCache',
        ],
    ];
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
