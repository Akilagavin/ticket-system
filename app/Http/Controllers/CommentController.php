<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     * Handles both registered agents and guest customers.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validate the incoming request
        $request->validate([
            'content' => 'required|string',
            'ticket_id' => 'required|exists:tickets,id',
        ]);

        // 2. Prepare data for storage
        // We explicitly set user_id to null if the user is not logged in (Guest)
        $comment = Comment::create([
            'content' => $request->content,
            'ticket_id' => $request->ticket_id,
            'user_id' => Auth::check() ? Auth::id() : null,
        ]);

        // 3. THE FIX: Redirect back to the specific ticket view
        // Using redirect()->back() ensures the user stays on the ticket page 
        // and doesn't get a 'Method Not Allowed' error on refresh.
        if ($comment) {
            return redirect()->back()
                ->with('success', 'Your reply has been added successfully.');
        }

        return redirect()->back()
            ->with('error', 'Unable to save your reply. Please try again.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // Future implementation for editing replies
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        // Future implementation for deleting replies
    }
}