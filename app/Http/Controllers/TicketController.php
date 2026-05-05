<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController 
{
    /**
     * Display a listing of tickets (Support Agent View).
     */
    public function index(Request $request)
    {
        $query = Ticket::query();

        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('ref', 'like', "%{$searchTerm}%")
                  ->orWhere('customer_name', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $sortableColumns = ['customer_name', 'created_at', 'updated_at', 'status'];
        $sortField = $request->query('sort', 'created_at');
        $sortDirection = $request->query('sort_dir') === 'asc' ? 'asc' : 'desc';

        if (in_array($sortField, $sortableColumns)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = $request->query('per_page', 10);
        $tickets = $query->paginate($perPage)->withQueryString();

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created ticket.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'nullable|string|max:20',
            'description'   => 'required|string',
        ]);

        $ticket = new Ticket();
        $ticket->customer_name = $request->input('customer_name');
        $ticket->email = $request->input('email');
        $ticket->phone = $request->input('phone');
        $ticket->description = $request->input('description');
        
        // Generate secure SHA1 reference
        $ticket->ref = sha1(time() . Str::random(10));
        $ticket->status = 0; // Default: Open

        if ($ticket->save()) {
            return redirect()->route('tickets.show', $ticket->ref)
                ->with('success', 'Your ticket is created successfully.');
        }

        return redirect()->back()
            ->with('error', 'Oops! Could not create your ticket.');
    }

    /**
     * Display a specific ticket using the SHA1 reference string.
     */
    public function show($ref)
    {
        $ticket = Ticket::where('ref', $ref)->firstOrFail();
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing (Agent view).
     * FIX: Variable name $ticket matches {ticket} in web.php to prevent 404.
     */
    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact('ticket'));
    }

    /**
     * Update the ticket status (Handled by Agent).
     * FIX: Variable name $ticket matches {ticket} in web.php.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|integer|in:0,1,2,3', // Included 3 for Cancelled
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.show', $ticket->ref)
            ->with('success', 'Ticket status updated successfully.');
    }

    /**
     * Search for a ticket by its Reference ID.
     */
    public function search(Request $request)
    {
        $ref = $request->query('reference'); 
        $ticket = Ticket::where('ref', $ref)->firstOrFail();
        return redirect()->route('tickets.show', $ticket->ref);
    }
}