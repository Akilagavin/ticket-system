<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     * Supports both Guest Customers and Logged-in Agents.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validation
        $request->validate([
            'content'   => 'required|string|min:1',
            'ticket_id' => 'required|exists:tickets,id',
        ]);

        // 2. Data Preparation & Creation
        // We ensure user_id is NULL for guests to satisfy the system requirements
        $comment = Comment::create([
            'content'   => $request->content,
            'ticket_id' => $request->ticket_id,
            'user_id'   => Auth::check() ? Auth::id() : null,
        ]);

        // 3. Redirection Fix
        // Redirecting back prevents MethodNotAllowedHttpException on page refresh
        if ($comment) {
            return redirect()->back()
                ->with('success', 'Your reply has been added successfully.');
        }

        return redirect()->back()
            ->with('error', 'Something went wrong. Please try again.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // Future Logic: Ensure only the comment author or an admin can update
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        // Future Logic: Check permissions before deleting
    }
}