<?php

namespace App\Providers;

use App\Event\ThreadReceivedNewReply;
use App\Listeners\NotifyMentionedUser;
use App\Listeners\NotifySubscribers;
use App\Listeners\SendEmailConfirmationRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            SendEmailConfirmationRequest::class,
        ],
        ThreadReceivedNewReply::class => [
            NotifyMentionedUser::class,
            NotifySubscribers::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
