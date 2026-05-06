<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTicketStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ensure the user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $ticket = $request->route('ticket');

        // 2. Role differentiation logic
        // Role: 1 = Customer/User, 2 = Agent/Support
        $isAgent = $user->role === 2;

        if ($ticket instanceof Ticket) {
            // Logic for regular Customers: Prevent editing if Resolved (2) or Cancelled (3)
            if (!$isAgent && in_array($ticket->status, [2, 3])) {
                return redirect()->route('tickets.index')
                    ->with('error', 'This ticket is finalized and cannot be edited.');
            }

            // Logic for Agents: Allow modification of most tickets, but prevent modifying cancelled ones
            if ($isAgent && $ticket->status === 3) {
                return redirect()->route('tickets.index')
                    ->with('error', 'Cancelled tickets cannot be modified by agents.');
            }
        }

        return $next($request);
    }
}