<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketCreated as NewTicketMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewTicketMail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TicketCreated  $event
     * @return void
     */
    public function handle(TicketCreated $event): void
    {
        // Access the ticket object from the event
        if (isset($event->ticket->email)) {
            // Send the new ticket notification to the user
            Mail::to($event->ticket->email)->send(new NewTicketMail($event->ticket));
        }
    }
}