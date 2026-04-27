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
     */
    public function store(Request $request)
    {
        // 1. Validation: Ensure the customer provides the necessary info
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'nullable|string|max:20',
            'description'   => 'required|string',
            'category_id'   => 'nullable|exists:categories,id', // Added validation for category
        ]);

        // 2. Business Logic: Generate a unique Reference ID (e.g., TKT-A1B2C3)
        $validated['ref'] = 'TKT-' . strtoupper(Str::random(6));
        $validated['status'] = 'open';

        // 3. Save to Database
        $validated['category_id'] = $request->category_id ?? 1;
        $ticket = Ticket::create($validated);

        // 4. Redirect to the 'show' page with a success message
        return redirect()->route('tickets.show', $ticket->ref)
            ->with('success', 'Your ticket has been created! Ref: ' . $ticket->ref);
    }

    /**
     * Display a specific ticket.
     * Scoped by 'ref' because of your web.php settings.
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
     * Update the ticket (e.g., change status or add agent notes).
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:open,closed,pending',
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.show', $ticket->ref)
            ->with('success', 'Ticket updated successfully.');
    }

    /**
     * Search for a ticket by its Reference ID.
     */
    public function search(Request $request)
    {
        $ref = $request->query('ref');
        
        // Try to find the ticket; throw a 404 if it doesn't exist
        $ticket = Ticket::where('ref', $ref)->firstOrFail();

        return redirect()->route('tickets.show', $ticket->ref);
    }
}