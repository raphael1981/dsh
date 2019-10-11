<?php

namespace App\Listeners;

use App\Events\SubscribeNewsletterDelete;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RaportAdminDelete
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SubscribeNewsletterDelete  $event
     * @return void
     */
    public function handle(SubscribeNewsletterDelete $event)
    {
        //
    }
}
