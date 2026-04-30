<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category; // Added back from your HEAD changes
use App\Mail\TicketCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TicketController 
{
    /**
     * Display a listing of tickets (Support Agent View).
     * Updated for Part 2 to load the index view with tickets.
     */
    public function index(Request $request)
    {
        // Using latest() ensure agents see the most recent issues first.
        // paginate(10) ensures the page remains fast even with thousands of tickets.
        $tickets = Ticket::latest()->paginate(10); 

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new ticket (Customer view).
     */
    public function create()
    {
        $categories = Category::all(); // Keeps the category feature from HEAD
        return view('tickets.create', compact('categories'));
    }

    /**
     * Store a newly created ticket in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'nullable|string|max:20',
            'description'   => 'required|string',
        ]);

        // 2. Manual Assignment
        $ticket = new Ticket();
        $ticket->customer_name = $request->input('customer_name');
        $ticket->email = $request->input('email');
        $ticket->phone = $request->input('phone');
        $ticket->description = $request->input('description');
        
        // Internal Logic: SHA1 Hash for reference
        $ticket->ref = sha1(time());
        $ticket->status = 0; // Default to 'Open'

        // 3. Save and Notify (Queued Mail logic handled in the Model/Observer or via ShouldQueue)
        if ($ticket->save()) {
            return redirect(route('tickets.show', $ticket->ref))
                ->with('success', 'Your ticket is created successfully. Please write down the reference number to check the ticket status later.');
        }

        return redirect()->back()->with('error', 'Oops! Could not create your ticket.');
    }

    /**
     * Display a specific ticket using the SHA1 reference.
     */
    public function show($ref)
    {
        $ticket = Ticket::where('ref', $ref)->firstOrFail();

        return view('tickets.show', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * Show the form for editing (Admin/Agent view).
     */
    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact('ticket'));
    }

    /**
     * Update the ticket status (Handled by Agent).
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
     * Search for a ticket by its Reference ID (Used on the Welcome page).
     */
    public function search(Request $request)
    {
        $ref = $request->query('reference'); // Matches the 'name' attribute in your Welcome form

        $ticket = Ticket::where('ref', $ref)->firstOrFail();

        return redirect()->route('tickets.show', $ticket->ref);
    }
}