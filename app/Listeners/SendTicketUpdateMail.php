<?php

namespace App\Listeners;

use App\Events\TicketUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail; 
use App\Mail\TicketCreated; // Changed from NewTicketMail to match your actual file

class SendTicketUpdateMail
{
    public function __construct()
    {
        //
    }

    public function handle(TicketUpdated $event): void
    {
        // Reference the correct class name: TicketCreated
        Mail::to($event->ticket->email)
            ->later(now()->addSeconds(2), new TicketCreated($event->ticket));
    }
}