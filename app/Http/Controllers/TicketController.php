<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Category;

class TicketController extends Controller
{
        // Show all tickets
    public function index()
    {
        $tickets = Ticket::latest()->get();
        return view('tickets.index', compact('tickets'));
    }

    // Show create form
    public function create()
    {
        // $categories = Category::all();
        // return view('tickets.create', compact('categories'));
        return view('tickets.create');
    }

    // Store new ticket
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        $ticket = new Ticket($request->all());
        $ticket->ref = sha1(time());
        $ticket->status = 0;
        $ticket->save();

        return redirect()
            ->route('tickets.show', $ticket->ref)
            ->with('success', 'Ticket created successfully!');
    }

    // Show single ticket
    public function show($ref)
    {
        $ticket = Ticket::where('ref', $ref)->firstOrFail();
        return view('tickets.show', compact('ticket'));
    }

    // Show edit form
    public function edit($ref)
    {
        $ticket = Ticket::where('ref', $ref)->firstOrFail();
        $categories = Category::all();

        return view('tickets.edit', compact('ticket', 'categories'));
    }

    // Update ticket
    public function update(Request $request, $ref)
    {
        $ticket = Ticket::where('ref', $ref)->firstOrFail();

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        $ticket->update($request->all());

        return redirect()
            ->route('tickets.show', $ticket->ref)
            ->with('success', 'Ticket updated successfully!');
    }

    // Delete ticket
    public function destroy($ref)
    {
        $ticket = Ticket::where('ref', $ref)->firstOrFail();
        $ticket->delete();

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket deleted successfully!');
    }

    // Search ticket by reference
    public function search(Request $request)
    {
        $request->validate([
            'reference' => 'required'
        ]);

        $ticket = Ticket::where('ref', $request->reference)->first();

        if ($ticket) {
            return redirect()->route('tickets.show', $ticket->ref);
        }

        return back()->with('error', 'Ticket not found');
    }
    
}
