<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets (Admin view).
     */
    public function index()
    {
        // Fetch tickets, newest first, with pagination
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
     * Uses Dependency Injection to access the $request object.
     */
    public function store(Request $request)
    {
        // 1. Validation: Ensures data integrity before processing
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'nullable|string|max:20',
            'description'   => 'required|string',
        ]);

        // 2. Manual Assignment: Creating the Ticket object
        $ticket = new Ticket();
        $ticket->customer_name = $request->input('customer_name');
        $ticket->email = $request->input('email');
        $ticket->phone = $request->input('phone');
        $ticket->description = $request->input('description');
        
        // Internal Logic: Setting non-form attributes
        // Generating a unique hash reference and setting initial status to 0 (Open)
        $ticket->ref = sha1(time());
        $ticket->status = 0; 

        // 3. Save Logic with Flashed Session Feedback
        if ($ticket->save()) {
            // Redirect to the 'show' view using the newly created ref
            return redirect(route('tickets.show', $ticket->ref))
                ->with('success', 'Your ticket is created successfully. Please write down the reference number to check the ticket status later.');
        }

        // Fallback: If the database save fails, return back with an error alert
        return redirect()->back()
            ->with('error', 'Oops! Could not create your ticket. Please try again later.');
    }

    /**
     * Display a specific ticket.
     */
    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
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
     * Validates against integers 0, 1, and 2.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|integer|in:0,1,2',
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
        $ref = $request->query('ref');

        // Find the ticket by ref; fails with 404 if not found
        $ticket = Ticket::where('ref', $ref)->firstOrFail();

        return redirect()->route('tickets.show', $ticket->ref);
    }
}