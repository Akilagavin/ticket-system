<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTicketStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Get the ticket from the route (e.g., /tickets/{ticket})
        // Laravel's route model binding allows us to grab the object directly
        $ticket = $request->route('ticket');

        // 2. Check if we actually found a ticket and if its status is 'closed'
        // We use 'resolved' or 'closed' depending on your database column
        if ($ticket instanceof Ticket && $ticket->status === 'closed') {
            
            // 3. If closed, redirect back to the ticket list with a warning message
            return redirect()->route('tickets.index')
                ->with('error', 'Modification denied: This ticket has been finalized and closed.');
        }

        // 4. If open, proceed to the Controller
        return $next($request);
    }
}