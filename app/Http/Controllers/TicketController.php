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
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $tickets = Ticket::latest()->paginate($perPage);

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
            'category_id'   => 'nullable|exists:categories,id',
        ]);

        // Logic: Generate SHA1 hash reference and set status to 0 (Open)
        $validated['ref'] = sha1(time() . Str::random(10));
        $validated['status'] = 0; 
        $validated['category_id'] = $request->category_id ?? 1;

        $ticket = Ticket::create($validated);

        if ($ticket) {
            // Send the email to the customer (The core logic from your branch)
            Mail::to($ticket->email)->send(new TicketCreated($ticket));

            return redirect()->route('tickets.show', $ticket->ref)
                ->with('success', 'Ticket created! Check your email for the reference number.');
        }

        return redirect()->back()->with('error', 'Oops! Could not create your ticket.');
    }

    /**
     * Display a specific ticket using the SHA1 reference.
     */
    public function show($ref)
    {
        $ticket = Ticket::where('ref', $ref)->firstOrFail();
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Search for a ticket by its Reference ID (Used on the Welcome page).
     */
    public function search(Request $request)
    {
        $ref = $request->query('reference');
        $ticket = Ticket::where('ref', $ref)->first();

        if (!$ticket) {
            return redirect()->back()->with('error', 'No ticket found with that reference number.');
        }

        return redirect()->route('tickets.show', $ticket->ref);
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
}