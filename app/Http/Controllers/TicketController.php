<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets (Support Agent View).
     * Matches the UI in support-table-with-search-sort-form.png.
     */
    public function index(Request $request)
    {
        // 1. Initialize the query
        $query = Ticket::query();

        // 2. Handle Search (Filter by Ref, Name, Phone, or Description)
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('ref', 'like', "%{$searchTerm}%")
                  ->orWhere('customer_name', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // 3. Handle Sorting with Sanitize Check
        $sortableColumns = ['customer_name', 'created_at', 'status'];
        $sortField = $request->query('sort', 'created_at');
        $sortDirection = $request->query('sort_dir') === 'asc' ? 'asc' : 'desc';

        if (in_array($sortField, $sortableColumns)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // 4. Pagination
        // withQueryString() ensures search/sort stays active when clicking page numbers
        $perPage = $request->query('per_page', 10);
        $tickets = $query->paginate($perPage)->withQueryString();

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
        $request->validate([
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
        
        // Internal Logic: SHA1 Hash for a secure, non-sequential reference
        $ticket->ref = sha1(time() . Str::random(10));
        $ticket->status = 0; // Default to 'Open' (0: Open, 1: Pending, 2: Resolved)

        // 3. Save and Redirect
        if ($ticket->save()) {
            return redirect(route('tickets.show', $ticket->ref))
                ->with('success', 'Your ticket is created successfully. Reference: ' . $ticket->ref);
        }

        return redirect()->back()
            ->with('error', 'Oops! Could not create your ticket. Please try again later.');
    }

    /**
     * Display a specific ticket using the SHA1 reference.
     */
    public function show($ref)
    {
        // Use firstOrFail so it automatically throws a 404 if the SHA1 hash is invalid
        $ticket = Ticket::where('ref', $ref)->firstOrFail();
        return view('tickets.show', compact('ticket'));
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
        $ref = $request->query('reference'); 
        $ticket = Ticket::where('ref', $ref)->firstOrFail();
        return redirect()->route('tickets.show', $ticket->ref);
    }
}