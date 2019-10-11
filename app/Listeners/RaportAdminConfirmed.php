<?php

namespace App\Listeners;

use App\Events\SubscribeNewsletterConfirmed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class RaportAdminConfirmed
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
     * @param  SubscribeNewsletterConfirmed  $event
     * @return void
     */
    public function handle(SubscribeNewsletterConfirmed $event)
    {
        $member = $event->member;

        Mail::send('emails.newsletter.adminconfirm', ['member' =>  $member], function ($m) use ($member) {

            $m->from(config('services')['adminmail'], 'DSH');
            $m->to(config('services')['adminmail'], '')->subject('Zapis na newsletter potwierdzony - użytkownik '.$member->email);

        });
    }
}
