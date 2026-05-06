<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Events\TicketUpdated;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     * Both agents (logged in) and customers (guests) use this.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'content'   => 'required|string',
            'ticket_id' => 'required|exists:tickets,id',
        ]);

        // Create comment with mass assignment
        $comment = Comment::create([
            'content'   => $request->content,
            'ticket_id' => $request->ticket_id,
            'user_id'   => Auth::check() ? Auth::id() : null,
        ]);

        // Get the associated ticket
        $ticket = $comment->ticket;

        // Dispatch the TicketUpdated event to trigger the email
        TicketUpdated::dispatch($ticket);

        return back()->with('success', 'Your reply has been added successfully.');
    }

    /**
     * Update the specified comment in storage.
     * Typically used by Agents to fix typos in their replies.
     */
    public function update(Request $request, Comment $comment): RedirectResponse
    {
        // Validation
        $request->validate([
            'content' => 'required|string'
        ]);

        // Authorization: Ensure only the person who wrote it (or an agent) can edit
        if (Auth::id() !== $comment->user_id && !(Auth::check() && Auth::user()->role === 2)) {
            return back()->with('error', 'You are not authorized to edit this comment.');
        }
        
        $comment->update([
            'content' => $request->content
        ]);

        return back()->with('success', 'Comment updated successfully.');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        // Authorization: Ensure only the person who wrote it (or an agent) can delete
        if (Auth::id() !== $comment->user_id && !(Auth::check() && Auth::user()->role === 2)) {
            return back()->with('error', 'You are not authorized to delete this comment.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}