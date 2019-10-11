<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SubscribeNewsletterSave' => [
            'App\Listeners\RaportMemberSave',
            'App\Listeners\RaportAdminSave',
        ],
        'App\Events\SubscribeNewsletterConfirmed' => [
            'App\Listeners\RaportMemberConfirmed',
            'App\Listeners\RaportAdminConfirmed',
        ],
        'App\Events\SubscribeNewsletterDelete' => [
            'App\Listeners\RaportMemberDelete',
            'App\Listeners\RaportAdminDelete',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Event::listen('routes.translation', function ($locale, $attributes) {
//            dd($attributes);
        });
    }
}
