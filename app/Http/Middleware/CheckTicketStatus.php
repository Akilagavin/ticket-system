<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTicketStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $ticket = $request->route('ticket');

        // Check for integer status: 2 = Resolved, 3 = Cancelled
        if ($ticket instanceof Ticket && in_array($ticket->status, [2, 3])) {
            return redirect()->route('tickets.index')
                ->with('error', 'This ticket is finalized and cannot be edited.');
    }

    return $next($request);
    }
}