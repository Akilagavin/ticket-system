<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth; // Added for IDE clarity

class CommentController  // Ensure it extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validate the incoming request
        $request->validate([
            'content' => 'required|string',
            'ticket_id' => 'required|exists:tickets,id',
        ]);

        // 2. Prepare data for storage
        $data = $request->only(['content', 'ticket_id']);
        
        /**
         * 3. Set user_id only for logged-in agents.
         * Using Auth facade to resolve the "undefined method" warning.
         */
        $data['user_id'] = Auth::check() ? Auth::id() : null;

        // 4. Create the comment using mass assignment
        $comment = Comment::create($data);

        // 5. Handle the response based on success or failure
        if ($comment) {
            return redirect()->route('tickets.show', $comment->ticket_id)
                ->with('success', 'Your reply added successfully.');
        }

        return redirect()->back()
            ->with('error', 'Opps! we could not save your reply. Please try again.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // Logic for future agent editing
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        // Logic for future agent deletion
    }
}