<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Mail\TicketCreated; // Added Mailable
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // Added Mail Facade
use Illuminate\Support\Str;

class TicketController 
{
    /**
     * Display a listing of tickets (Admin view).
     */
    public function index()
    {
        $tickets = Ticket::latest()->paginate(10);
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new ticket (Customer view).
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created ticket in the database.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'nullable|string|max:20',
            'description'   => 'required|string',
            'category_id'   => 'nullable|exists:categories,id',
        ]);

        // 2. Business Logic: Generate SHA1 hash reference and set status to 0 (Open)
        $validated['ref'] = sha1(time());
        $validated['status'] = 0; 
        $validated['category_id'] = $request->category_id ?? 1;

        // 3. Save to Database
        $ticket = Ticket::create($validated);

        if ($ticket) {
            // 4. Send the email to the customer
            Mail::to($ticket->email)->send(new TicketCreated($ticket));

            // 5. Redirect to 'show' view with success feedback
            return redirect()->route('tickets.show', $ticket->ref)
                ->with('success', 'Your ticket is created successfully. Check your email for the reference number!');
        }

        return redirect()->back()->with('error', 'Oops! Could not create your ticket.');
    }

    /**
     * Display a specific ticket.
     */
    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Search for a ticket by its Reference ID.
     */
    public function search(Request $request)
    {
        // Matches the 'name="reference"' in your welcome.blade.php form
        $ref = $request->query('reference');
        
        $ticket = Ticket::where('ref', $ref)->first();

        if (!$ticket) {
            return redirect()->back()->with('error', 'No ticket found with that reference number.');
        }

        return redirect()->route('tickets.show', $ticket->ref);
    }

    /**
     * Show the form for editing (Admin view).
     */
    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact('ticket'));
    }

    /**
     * Update the ticket status.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|integer|in:0,1,2', // Matching your 0=Open, 1=Pending, 2=Closed logic
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.show', $ticket->ref)
            ->with('success', 'Ticket status updated successfully.');
    }
}