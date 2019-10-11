<?php

namespace App\Listeners;

use App\Events\SubscribeNewsletterSave;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class RaportAdminSave
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
     * @param  SubscribeNewsletterSave  $event
     * @return void
     */
    public function handle(SubscribeNewsletterSave $event)
    {
        $member = $event->member;

        Mail::send('emails.newsletter.adminsave', ['member' =>  $member], function ($m) use ($member) {

            $m->from(config('services')['adminmail'], 'DSH');
            $m->to(config('services')['adminmail'], '')->subject('Zapis na newsletter - nie potwierdzony  - użytkownik '.$member->email);

        });
    }
}
